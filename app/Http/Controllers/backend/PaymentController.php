<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::query();

        if ($request->filled('type')) {
            $query->whereHas('transaction', function ($q) use ($request) {
                $q->where('type', $request->type); // purchase | sale
            });
        }

        $payments = $query->latest()->simplePaginate(10);

        return view('backend.payment.index', compact('payments'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        return view('backend.payment.edit', [
            'payment' => $payment,
            'transaction' => $payment->transaction, // در صورت نیاز مستقیم در ویو استفاده شود
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Payment $payment)
{
    // تبدیل اعداد فارسی به انگلیسی
    $request->merge([
        'amount' => persianToEnglishNumber($request->amount),
        'exchange_rate' => $request->exchange_rate ? persianToEnglishNumber($request->exchange_rate) : null,
    ]);

    // مجموع پرداخت‌های قبلی به AFN (به‌جز همین پرداخت)
    $totalPaidAFN = $payment->transaction->payments
        ->where('id', '!=', $payment->id)
        ->sum(function ($p) {
            return $p->currency === 'USD'
                ? $p->amount * $p->exchange_rate
                : $p->amount;
        });

    // اعتبارسنجی
    $request->validate([
        'amount' => [
            'required',
            'numeric',
            'min:0.01',
            function ($attribute, $value, $fail) use ($request, $payment, $totalPaidAFN) {
                $rate = $request->currency === 'USD'
                    ? $request->exchange_rate
                    : 1;

                $amountAFN = floatval($value) * $rate;
                $remaining = $payment->transaction->total_price - $totalPaidAFN;

                if ($amountAFN > $remaining) {
                    $fail(
                        'مقدار پرداخت نمی‌تواند بیشتر از باقی‌مانده تراکنش باشد. ' .
                        'باقی‌مانده: ' . number_format($remaining, 2) . ' AFN'
                    );
                }
            }
        ],
        'currency' => 'required|string|in:AFN,USD',
        'payment_date' => 'required|date',
        'exchange_rate' => 'required_if:currency,USD|nullable|numeric|min:0.01',
    ], [
        'amount.required' => 'وارد کردن مقدار الزامی است.',
        'currency.required' => 'وارد کردن واحد پول الزامی است.',
        'payment_date.required' => 'وارد کردن تاریخ پرداخت الزامی است.',
        'amount.numeric' => 'مقدار باید عددی باشد.',
        'amount.min' => 'مقدار پرداختی باید بیشتر از صفر باشد.',
        'exchange_rate.required_if' => 'اگر واحد پول USD است، نرخ ارز الزامی است.',
        'exchange_rate.numeric' => 'نرخ ارز باید عددی باشد.',
        'exchange_rate.min' => 'نرخ ارز باید بیشتر از صفر باشد.',
    ]);

    // به‌روزرسانی پرداخت (بدون تبدیل واحد)
    $payment->update([
        'amount' => $request->amount,
        'currency' => $request->currency,
        'payment_date' => $request->payment_date,
        'exchange_rate' => $request->currency === 'USD'
            ? $request->exchange_rate
            : null,
    ]);

    return redirect()->route('transaction.show', [$payment->transaction_id])
                     ->with('success', 'پرداخت با موفقیت ویرایش شد.');
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json([
            'success' => true,
            'message' => 'پرداختی با موفقیت حذف شد.'
        ]);
    }

    // فورم ثبت پرداختی ها
    public function create_payment($transaction_id){
        $transaction = Transaction::findOrFail($transaction_id);
        $totalPaid = $transaction->payments()->sum('amount');
        return view('backend.payment.create', compact('transaction','totalPaid')); 
    }
// ------------------------------------------------------------------------

public function store_payment(Request $request, Transaction $transaction)
{
    // ابتدا مجموع پرداخت‌های قبلی به AFN (با نرخ ارز اعمال شده) را محاسبه کنیم
    $totalPaidAFN = $transaction->payments->sum(function($p) {
        return $p->currency === 'USD' ? $p->amount * $p->exchange_rate : $p->amount;
    });

    // اعتبارسنجی
    $request->validate([
        'amount' => [
            'required',
            'numeric',
            'min:0.01',
            // اعتبارسنجی اضافی: نباید از باقی‌مانده بیشتر باشد
            function($attribute, $value, $fail) use ($request, $transaction, $totalPaidAFN) {
                $rate = $request->currency === 'USD' ? $request->exchange_rate : 1;
                $amountAFN = floatval($value) * $rate;
                $remaining = $transaction->total_price - $totalPaidAFN;
                if($amountAFN > $remaining) {
                    $fail("مقدار پرداخت نمی‌تواند بیشتر از باقی‌مانده تراکنش باشد. باقی‌مانده: " . number_format($remaining, 2) . " AFN");
                }
            },
        ],
        'currency' => 'required|string|in:AFN,USD',
        'payment_date' => 'required|date',
        'exchange_rate' => 'required_if:currency,USD|nullable|numeric|min:0.01',
    ],[
        'amount.required' => 'وارد کردن مقدار الزامی است.',
        'currency.required' => 'وارد کردن نام واحد پول الزامی است.',
        'payment_date.required' => 'وارد کردن تاریخ پرداختی الزامی است.',
        'amount.numeric' => 'مقدار باید عددی باشد.',
        'amount.min' => 'مقدار پرداختی باید بیشتر از صفر باشد.',
        'exchange_rate.required_if' => 'اگر واحد پول USD است، نرخ ارز الزامی است.',
        'exchange_rate.numeric' => 'نرخ ارز باید عددی باشد.',
        'exchange_rate.min' => 'نرخ ارز باید بیشتر از صفر باشد.',
    ]);

    // اگر AFN باشد، exchange_rate را null قرار بده
    $exchangeRate = $request->currency === 'USD' ? $request->exchange_rate : null;

    // ذخیره پرداخت
    $transaction->payments()->create([
        'amount' => $request->amount,
        'currency' => $request->currency,
        'payment_date' => $request->payment_date,
        'exchange_rate' => $exchangeRate,
    ]);

    return redirect()->route('transaction.show', [$transaction->id])
                     ->with('success', 'پرداخت با موفقیت ثبت شد.');
}





}
