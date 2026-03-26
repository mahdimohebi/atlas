<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\CustomerPayment;
use Illuminate\Http\Request;

class CustomerPaymentController extends Controller
{
public function index($sale_id)
{
    $sale = Sale::with('customer', 'items', 'payments')->findOrFail($sale_id);
    $totalPaid = $sale->payments->sum('amount');

    return view('backend.customer_payment.index', compact('sale', 'totalPaid'));
}


    public function create($sale_id)
    {
        // پیدا کردن فروش
        $sale = Sale::with('customer', 'items', 'payments')->findOrFail($sale_id);

        // مجموع پرداخت‌های قبلی
        $totalPaid = $sale->payments()->sum('amount');

        return view('backend.customer_payment.create', compact('sale', 'totalPaid'));
    }

    public function store(Request $request)
    {
           $request->merge([
            'amount' => persianToEnglishNumber($request->amount),
        ]);
        // اعتبارسنجی داده‌ها
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string',
        ], [
            'sale_id.required' => 'شناسه فاکتور الزامی است.',
            'sale_id.exists' => 'فاکتور انتخاب شده معتبر نیست.',
            'customer_id.required' => 'شناسه مشتری الزامی است.',
            'customer_id.exists' => 'مشتری انتخاب شده معتبر نیست.',
            'amount.required' => 'مبلغ پرداختی الزامی است.',
            'amount.numeric' => 'مبلغ باید عددی باشد.',
            'amount.min' => 'مبلغ باید حداقل 0.01 باشد.',
            'payment_date.required' => 'تاریخ پرداخت الزامی است.',
            'payment_date.date' => 'تاریخ پرداخت معتبر نیست.',
        ]);

        // پیدا کردن فروش مربوطه
        $sale = Sale::findOrFail($request->sale_id);

        // جمع کل فروش و جمع پرداخت شده قبلی
        $totalPrice = $sale->items->sum('total_price') - ($sale->discount ?? 0);
        $totalPaid = $sale->payments->sum('amount');

        // بررسی اینکه پرداخت از باقی‌مانده بیشتر نباشد
        if ($request->amount > ($totalPrice - $totalPaid)) {
            return back()->withErrors(['amount' => 'مبلغ پرداخت نمی‌تواند بیشتر از باقی‌مانده باشد.'])->withInput();
        }

        // ایجاد رکورد پرداخت
        CustomerPayment::create([
            'sale_id' => $request->sale_id,
            'customer_id' => $request->customer_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('customer_payment.index', $request->sale_id)
                 ->with('success', 'پرداخت با موفقیت ثبت شد.');

    }

    public function edit($id)
    {
        // پیدا کردن پرداخت بر اساس ID
        $payment = CustomerPayment::findOrFail($id);

        // پیدا کردن فاکتور مرتبط با پرداخت
        $sale = Sale::findOrFail($payment->sale_id);

        // بازگرداندن ویو و ارسال اطلاعات
        return view('backend.customer_payment.edit', compact('payment', 'sale'));
    }

    public function update(Request $request, $id)
    {
        // پیدا کردن پرداخت
        $payment = CustomerPayment::findOrFail($id);

        // تبدیل اعداد فارسی به انگلیسی
        $request->merge([
            'amount' => persianToEnglishNumber($request->amount),
        ]);

        // اعتبارسنجی داده‌ها
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string',
        ], [
            'sale_id.required' => 'شناسه فاکتور الزامی است.',
            'sale_id.exists' => 'فاکتور انتخاب شده معتبر نیست.',
            'customer_id.required' => 'شناسه مشتری الزامی است.',
            'customer_id.exists' => 'مشتری انتخاب شده معتبر نیست.',
            'amount.required' => 'مبلغ پرداختی الزامی است.',
            'amount.numeric' => 'مبلغ باید عددی باشد.',
            'amount.min' => 'مبلغ باید حداقل 0.01 باشد.',
            'payment_date.required' => 'تاریخ پرداخت الزامی است.',
            'payment_date.date' => 'تاریخ پرداخت معتبر نیست.',
        ]);

        // پیدا کردن فروش مربوطه
        $sale = Sale::findOrFail($request->sale_id);

        // جمع کل فروش و جمع پرداخت شده قبلی به جز این پرداخت
        $totalPrice = $sale->items->sum('total_price') - ($sale->discount ?? 0);
        $totalPaidExcludingCurrent = $sale->payments->sum('amount') - $payment->amount;

        // بررسی اینکه پرداخت از باقی‌مانده بیشتر نباشد
        if ($request->amount > ($totalPrice - $totalPaidExcludingCurrent)) {
            return back()->withErrors(['amount' => 'مبلغ پرداخت نمی‌تواند بیشتر از باقی‌مانده باشد.'])->withInput();
        }

        // بروزرسانی رکورد پرداخت
        $payment->update([
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('customer_payment.index', $request->sale_id)
                        ->with('success', 'پرداخت با موفقیت بروزرسانی شد.');
    }

    public function destroy($id)
    {
        $payment = CustomerPayment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'پرداخت با موفقیت حذف شد.']);
    }





}
