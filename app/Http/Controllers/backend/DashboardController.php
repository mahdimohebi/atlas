<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\PouringPot;
use App\Models\DesignPot;
use App\Models\Saleitem;
use App\Models\CustomerPayment;
use App\Models\FactoryExpense;
use App\Models\FactoryPurchase;
use App\Models\FactoryPurchasePayment;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->filter ?? 'monthly';

        // ---------- تعیین بازه زمانی ----------
        switch ($filter) {
            case 'daily':
                $from = Carbon::today()->startOfDay();
                $to   = Carbon::today()->endOfDay();
                break;

            case 'weekly':
                $from = Carbon::now()->subDays(6)->startOfDay();
                $to   = Carbon::now()->endOfDay();
                break;

            case 'monthly':
                $from = Carbon::now()->startOfYear()->startOfDay();
                $to   = Carbon::now()->endOfYear()->endOfDay();
                break;

            case 'yearly':
                $from = Carbon::create(2024, 1, 1)->startOfDay();
                $to   = null;
                break;

            default:
                $from = Carbon::now()->startOfMonth()->startOfDay();
                $to   = Carbon::now()->endOfMonth()->endOfDay();
        }

        // ---------- اطلاعات پایه ----------
        $employee_count = Employee::where('is_active', 1)->count();
        $customer_count = Customer::count();

        $customer_payment_sum = CustomerPayment::where(function($q) use ($from, $to) {
            if ($to) $q->whereBetween('payment_date', [$from, $to]);
            else $q->where('payment_date', '>=', $from);
        })
        ->select(
            DB::raw('DATE(payment_date) as date'),
            DB::raw('SUM(amount) as total_amount')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $customer_payment_sum = $this->getCustomerPaymentChartData($filter, $from, $to);


        // ---------- DesignPot ----------
        $designpots = $this->getChartData(new DesignPot, 'quantity', 'date', $filter, $from, $to);

        // ---------- PouringPot ----------
        $pouringpot = $this->getChartData(new PouringPot, 'total_weight', 'date', $filter, $from, $to);

        // ---------- FactoryExpense ----------
        $expenses = $this->getChartData(new FactoryExpense, 'price', 'date', $filter, $from, $to);

        // ---------- FactoryPurchasePayment ----------
        $factoryPurchase = $this->getChartData(new FactoryPurchasePayment, 'amount', 'payment_date', $filter, $from, $to);

        // ---------- Employee Rozmozd ----------
        $employee_rozmozd = $this->getChartData(new Salary, 'amount', 'date', $filter, $from, $to)
            ->where('type', 'rozmozd')
            ->where('status', 1);

        $employee_rozmozd = $this->getSalaryChartData('rozmozd', $filter, $from, $to);


        $total_employee_rozmozd = Salary::where('type','rozmozd')->sum('amount');
        $total_employee_rozmozd_paid = $employee_rozmozd->sum('total_salary');
        $total_employee_remain = Salary::where('type','rozmozd')->where('status',0)->sum('amount');

        // ---------- Employee Ejaraei ----------
        $employee_rozmozd = $this->getChartData(new Salary, 'amount', 'date', $filter, $from, $to)
            ->where('type', 'ejaraei');

        $employee_ejaraei = $this->getSalaryChartData('ejaraei', $filter, $from, $to);
        $employee_rozmozd = $this->getSalaryChartData('rozmozd', $filter, $from, $to);


        $total_employee_ejaraei = 0;
        $total_employee_ejaraei_paid = 0;

        $ejaraeiEmployees = Employee::with(['contracts', 'salaries', 'pouringPots', 'designPots'])
            ->where('contract_type', 'ejaraei')->get();

        foreach ($ejaraeiEmployees as $emp) {
            $totalPaid = $emp->salaries->where('status', 1)->sum('amount');

            if ($emp->contracts->isNotEmpty()) {
                $pricePerKg = $emp->contracts->first()->price_per_kg ?? 0;

                if ($emp->job_position === 'raikhtgar') {
                    $totalAmount = $emp->pouringPots->sum('total_weight') * $pricePerKg;
                } else {
                    $totalAmount = $emp->designPots->sum('quantity') * $pricePerKg;
                }
            } else {
                $totalAmount = $emp->salaries->sum('amount');
            }

            $total_employee_ejaraei += $totalAmount;
            $total_employee_ejaraei_paid += $totalPaid;
        }

        $total_employee_ejaraei_remain = $total_employee_ejaraei - $total_employee_ejaraei_paid;

        $ejaraei_summary = [
            'total' => $total_employee_ejaraei,
            'paid'  => $total_employee_ejaraei_paid,
            'remain'=> $total_employee_ejaraei_remain
        ];

        // ---------- Total Income / Expense / Profit ----------
        $total_customer_payment = Saleitem::sum('total_price');
        $total_customer_payment_remain = $customer_payment_sum->sum('total_amount') - $total_customer_payment;

        $total_factory_purchase = FactoryPurchase::sum('total_price');
        $total_factory_purchase_payment = $factoryPurchase->sum('total');
        $total_factoryPurchase_remain = $total_factory_purchase - $total_factory_purchase_payment;

        $totalIncome  = $customer_payment_sum->sum('total_amount');
        $totalExpense = $expenses->sum('total');

        $net_profit = $totalIncome - $totalExpense - $total_factory_purchase_payment - $total_employee_rozmozd_paid - $total_employee_ejaraei_paid;

        // ---------- Return View ----------
        return view('backend.dashboard.index', compact(
            'filter',
            'employee_count',
            'customer_count',
            'customer_payment_sum',
            'designpots',
            'pouringpot',
            'expenses',
            'net_profit',
            'total_customer_payment',
            'total_customer_payment_remain',
            'factoryPurchase',
            'total_factory_purchase',
            'total_factoryPurchase_remain',
            'employee_rozmozd',
            'total_employee_rozmozd',
            'total_employee_remain',
            'total_employee_rozmozd_paid',
            'employee_ejaraei',
            'ejaraei_summary'
        ));
    }

    /**
     * تابع کمکی برای گرفتن داده‌های آماده chart
     */
    private function getChartData($model, $sumField, $dateField, $filter, $from, $to)
    {
        if ($filter === 'yearly') {
            $data = $model->where($dateField, '>=', $from)
                ->select(
                    DB::raw('YEAR(' . $dateField . ') as date'),
                    DB::raw('SUM(' . $sumField . ') as total')
                )
                ->groupBy(DB::raw('YEAR(' . $dateField . ')'))
                ->orderBy('date')
                ->get();
        } elseif ($filter === 'monthly') {
            $data = $model->whereBetween($dateField, [$from, $to])
                ->select(
                    DB::raw("DATE_FORMAT(" . $dateField . ",'%Y-%m') as date"),
                    DB::raw('SUM(' . $sumField . ') as total')
                )
                ->groupBy(DB::raw("DATE_FORMAT(" . $dateField . ",'%Y-%m')"))
                ->orderBy('date')
                ->get();
        } else { // daily
            $data = $model->whereBetween($dateField, [$from, $to])
                ->select(
                    DB::raw('DATE(' . $dateField . ') as date'),
                    DB::raw('SUM(' . $sumField . ') as total')
                )
                ->groupBy(DB::raw('DATE(' . $dateField . ')'))
                ->orderBy('date')
                ->get();
        }

        return $data->map(function($item) {
            $item->date = (string) $item->date;
            return $item;
        });
    }

    private function getCustomerPaymentChartData($filter, $from, $to)
{
    if ($filter === 'yearly') {
        $data = CustomerPayment::where('payment_date', '>=', $from)
            ->select(
                DB::raw('YEAR(payment_date) as date'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy(DB::raw('YEAR(payment_date)'))
            ->orderBy('date')
            ->get();
    } elseif ($filter === 'monthly') {
        $data = CustomerPayment::whereBetween('payment_date', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(payment_date,'%Y-%m') as date"),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy(DB::raw("DATE_FORMAT(payment_date,'%Y-%m')"))
            ->orderBy('date')
            ->get();
    } else { // daily or weekly
        $data = CustomerPayment::whereBetween('payment_date', [$from, $to])
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy(DB::raw('DATE(payment_date)'))
            ->orderBy('date')
            ->get();
    }

    return $data->map(function($item){
        $item->date = (string)$item->date;
        return $item;
    });
}



private function getSalaryChartData($type, $filter, $from, $to)
{
    if (!$to) {
        $to = Carbon::now()->endOfDay();
    }

    if ($filter === 'yearly') {
        $data = Salary::where('type', $type)
            ->where('status', 1)
            ->where('date', '>=', $from)
            ->select(
                DB::raw('YEAR(date) as date'),
                DB::raw('SUM(amount) as total_salary')
            )
            ->groupBy(DB::raw('YEAR(date)'))
            ->orderBy('date')
            ->get();
    } elseif ($filter === 'monthly') {
        $data = Salary::where('type', $type)
            ->where('status', 1)
            ->whereBetween('date', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(date,'%Y-%m') as date"),
                DB::raw('SUM(amount) as total_salary')
            )
            ->groupBy(DB::raw("DATE_FORMAT(date,'%Y-%m')"))
            ->orderBy('date')
            ->get();
    } else {
        $data = Salary::where('type', $type)
            ->where('status', 1)
            ->whereBetween('date', [$from, $to])
            ->select(
                DB::raw('DATE(date) as date'),
                DB::raw('SUM(amount) as total_salary')
            )
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date')
            ->get();
    }

    return $data->map(fn($item) => (object)[
        'date' => (string)$item->date,
        'total_salary' => $item->total_salary
    ]);
}





}
