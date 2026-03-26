<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FactoryPurchase;
use App\Models\FactoryPurchasePayment;
use Illuminate\Http\Request;


class FactoryPurchasePaymentController extends Controller
{
    // صفحه پرداخت‌ها
    public function paymentsPage($purchaseId)
    {
        $purchase = FactoryPurchase::with('payments')->findOrFail($purchaseId);
        return view('backend.factory_purchase.payments', compact('purchase'));
    }

    // گرفتن پرداخت‌ها (Ajax)
    public function getPayments($purchaseId)
    {
        $payments = FactoryPurchasePayment::where('factory_purchase_id', $purchaseId)
            ->orderBy('payment_date', 'desc')
            ->get();
        return response()->json($payments);
    }

    // ثبت پرداخت جدید
    public function storePayment(Request $request)
    {
        $request->validate([
            'factory_purchase_id' => 'required|exists:factory_purchases,id',
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0.01',
        ]);

        $payment = FactoryPurchasePayment::create([
            'factory_purchase_id' => $request->factory_purchase_id,
            'payment_date' => $request->payment_date,
            'amount'       => $request->amount,
            'note'         => $request->note ?? null,
        ]);

        return response()->json($payment);
    }

    // ویرایش پرداخت
    public function updatePayment(Request $request, $id)
    {
        $payment = FactoryPurchasePayment::findOrFail($id);

        $request->validate([
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0.01',
        ]);

        $payment->update([
            'payment_date' => $request->payment_date,
            'amount'       => $request->amount,
            'note'         => $request->note ?? null,
        ]);

        return response()->json($payment);
    }

    // حذف پرداخت
    public function deletePayment($id)
    {
        $payment = FactoryPurchasePayment::findOrFail($id);
        $payment->delete();

        return response()->json(['success' => true]);
    }
}


