<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\AluminumExpense;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AluminumDashboardController extends Controller
{
    public function index(Request $request)
    {
        /* =======================
         |  FILTER
         ======================= */
        $filter = $request->filter ?? 'monthly';

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
                // ماهانه → تمام ماه‌های سال جاری
                $from = Carbon::now()->startOfYear()->startOfDay();
                $to   = Carbon::now()->endOfYear()->endOfDay();
                break;

            case 'yearly':
                // سالانه → از ۲۰۲۴ تا امروز
                $from = Carbon::create(2024, 1, 1)->startOfDay();
                $to   = null;
                break;

            default:
                $from = Carbon::now()->startOfMonth()->startOfDay();
                $to   = Carbon::now()->endOfMonth()->endOfDay();
        }

        /* =======================
         |  SUMMARY
         ======================= */
        $totalSuppliers = Supplier::count();
        $totalClients   = Client::count();

        if($filter === 'yearly') {
            $totalpurchase = Transaction::where('type','purchase')
                ->where('transaction_date','>=',$from)
                ->sum('total_price');

            $totalsale = Transaction::where('type','sale')
                ->where('transaction_date','>=',$from)
                ->sum('total_price');
        } else {
            $totalpurchase = Transaction::where('type','purchase')
                ->whereBetween('transaction_date',[$from,$to])
                ->sum('total_price');

            $totalsale = Transaction::where('type','sale')
                ->whereBetween('transaction_date',[$from,$to])
                ->sum('total_price');
        }

        /* =======================
         |  PAYMENTS
         ======================= */
    if ($filter === 'yearly') {

        $purchasePayments = Payment::whereHas('transaction', fn($q)=>$q->where('type','purchase'))
            ->where('payment_date','>=',$from)
            ->select(
                DB::raw('YEAR(payment_date) as date'),
                DB::raw('SUM(CASE WHEN currency="USD" THEN amount*exchange_rate ELSE amount END) as total')
            )
            ->groupBy(DB::raw('YEAR(payment_date)'))
            ->orderBy('date')
            ->get();

        $salePayments = Payment::whereHas('transaction', fn($q)=>$q->where('type','sale'))
            ->where('payment_date','>=',$from)
            ->select(
                DB::raw('YEAR(payment_date) as date'),
                DB::raw('SUM(CASE WHEN currency="USD" THEN amount*exchange_rate ELSE amount END) as total')
            )
            ->groupBy(DB::raw('YEAR(payment_date)'))
            ->orderBy('date')
            ->get();

    } elseif ($filter === 'monthly') {

        $purchasePayments = Payment::whereHas('transaction', fn($q)=>$q->where('type','purchase'))
            ->whereBetween('payment_date', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(payment_date,'%Y-%m') as date"),
                DB::raw('SUM(CASE WHEN currency="USD" THEN amount*exchange_rate ELSE amount END) as total')
            )
            ->groupBy(DB::raw("DATE_FORMAT(payment_date,'%Y-%m')"))
            ->orderBy('date')
            ->get();

        $salePayments = Payment::whereHas('transaction', fn($q)=>$q->where('type','sale'))
            ->whereBetween('payment_date', [$from, $to])
            ->select(
                DB::raw("DATE_FORMAT(payment_date,'%Y-%m') as date"),
                DB::raw('SUM(CASE WHEN currency="USD" THEN amount*exchange_rate ELSE amount END) as total')
            )
            ->groupBy(DB::raw("DATE_FORMAT(payment_date,'%Y-%m')"))
            ->orderBy('date')
            ->get();

    } else { // daily

        $purchasePayments = Payment::whereHas('transaction', fn($q)=>$q->where('type','purchase'))
            ->whereBetween('payment_date', [$from, $to])
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(CASE WHEN currency="USD" THEN amount*exchange_rate ELSE amount END) as total')
            )
            ->groupBy(DB::raw('DATE(payment_date)'))
            ->orderBy('date')
            ->get();

        $salePayments = Payment::whereHas('transaction', fn($q)=>$q->where('type','sale'))
            ->whereBetween('payment_date', [$from, $to])
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(CASE WHEN currency="USD" THEN amount*exchange_rate ELSE amount END) as total')
            )
            ->groupBy(DB::raw('DATE(payment_date)'))
            ->orderBy('date')
            ->get();
    }


        $totalPurchasePaid = $purchasePayments->sum('total');
        $totalSalePaid     = $salePayments->sum('total');

        $remainingPurchasePaid = $totalpurchase - $totalPurchasePaid;
        $remaingSalePaid       = $totalsale - $totalSalePaid;

        /* =======================
         |  EXPENSE
         ======================= */
if ($filter === 'yearly') {

    $expense = AluminumExpense::where('date', '>=', $from)
        ->select(
            DB::raw('YEAR(date) as date'),
            'transaction_type',
            DB::raw('SUM(price) as total')
        )
        ->groupBy(DB::raw('YEAR(date)'), 'transaction_type')
        ->orderBy('date')
        ->get();

} elseif ($filter === 'monthly') {

    $expense = AluminumExpense::whereBetween('date', [$from, $to])
        ->select(
            DB::raw("DATE_FORMAT(date,'%Y-%m') as date"),
            'transaction_type',
            DB::raw('SUM(price) as total')
        )
        ->groupBy(DB::raw("DATE_FORMAT(date,'%Y-%m')"), 'transaction_type')
        ->orderBy('date')
        ->get();

} else { // daily

    $expense = AluminumExpense::whereBetween('date', [$from, $to])
        ->select(
            DB::raw('DATE(date) as date'),
            'transaction_type',
            DB::raw('SUM(price) as total')
        )
        ->groupBy(DB::raw('DATE(date)'), 'transaction_type')
        ->orderBy('date')
        ->get();
}

$saleExpenses = $expense->where('transaction_type', 'sale')->values();
$purchaseExpenses = $expense->where('transaction_type', 'purchase')->values();



        $totalSaleExpense = $saleExpenses->sum('total');
        $totalPurchaseExpense = $purchaseExpenses->sum('total');
        $total_expense = ($totalSaleExpense + $totalPurchaseExpense);

        $net_profit = $totalSalePaid - $totalPurchasePaid - ($totalSaleExpense + $totalPurchaseExpense);

        /* =======================
         |  ALUMINUM (HARD / SOFT)
         ======================= */
if ($filter === 'yearly') {

    $aluminum = Transaction::where('transaction_date','>=',$from)
        ->select(
            'type',
            'category',
            DB::raw('YEAR(transaction_date) as date'),
            DB::raw('SUM(quantity) as qty')
        )
        ->groupBy('type','category', DB::raw('YEAR(transaction_date)'))
        ->orderBy('date')
        ->get();

} elseif ($filter === 'monthly') {

    $aluminum = Transaction::whereBetween('transaction_date',[$from,$to])
        ->select(
            'type',
            'category',
            DB::raw("DATE_FORMAT(transaction_date,'%Y-%m') as date"),
            DB::raw('SUM(quantity) as qty')
        )
        ->groupBy('type','category', DB::raw("DATE_FORMAT(transaction_date,'%Y-%m')"))
        ->orderBy('date')
        ->get();

} else { // daily

    $aluminum = Transaction::whereBetween('transaction_date',[$from,$to])
        ->select(
            'type',
            'category',
            DB::raw('DATE(transaction_date) as date'),
            DB::raw('SUM(quantity) as qty')
        )
        ->groupBy('type','category', DB::raw('DATE(transaction_date)'))
        ->orderBy('date')
        ->get();
}
$aluminum = $aluminum->map(function ($item) {
    $item->date = (string) $item->date;
    return $item;
});

$chart = [
    'dates' => $aluminum->pluck('date')->unique()->values(),
    'hard' => ['purchase'=>[], 'sale'=>[], 'remaining'=>[]],
    'soft' => ['purchase'=>[], 'sale'=>[], 'remaining'=>[]]
];

foreach (['hard','soft'] as $cat) {

    $p = 0;
    $s = 0;

foreach (['hard','soft'] as $cat) {

    foreach ($chart['dates'] as $d) {

        $purchaseQty = $aluminum
            ->where('category',$cat)
            ->where('type','purchase')
            ->where('date',$d)
            ->sum('qty');

        $saleQty = $aluminum
            ->where('category',$cat)
            ->where('type','sale')
            ->where('date',$d)
            ->sum('qty');

        $chart[$cat]['purchase'][]  = $purchaseQty;
        $chart[$cat]['sale'][]      = $saleQty;
        $chart[$cat]['remaining'][] = $purchaseQty - $saleQty;
    }
}

}

        return view('backend.dashboard.aluminumdashboard', compact(
            'filter',
            'totalSuppliers',
            'totalClients',
            'totalpurchase',
            'totalsale',
            'totalPurchasePaid',
            'totalSalePaid',
            'remainingPurchasePaid',
            'remaingSalePaid',
            'total_expense',
            'net_profit',
            'purchasePayments',
            'salePayments',
            'expense',
            'chart',
            'saleExpenses', 'purchaseExpenses'
        ));
    }
}
