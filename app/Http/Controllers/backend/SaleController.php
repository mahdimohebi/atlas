<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Design;
use App\Models\PotType;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        // گرفتن تمام فاکتورها همراه مشتری
        $sales = Sale::with('customer')->latest()->paginate(10);

        return view('backend.sale.index', compact('sales'));
    }

    public function create($customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        $potTypes = PotType::with('potNumbers')->get();
        $design = Design::all();

        return view('backend.sale.create', compact('customer','potTypes','design'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'quantity' => array_map('persianToEnglishNumber', $request->quantity ?? []),
            'unit_price' => array_map('persianToEnglishNumber', $request->unit_price ?? []),
        ]);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'pot_type.*' => 'required|string',
            'quantity.*' => 'required|numeric|min:0',
            'unit_price.*' => 'required|numeric|min:0',
        ],[
            'customer_id.required' => 'انتخاب مشتری الزامی است.',
            'pot_type.*.required' => 'وارد کردن نوع دیگ الزامی است.',
            'quantity.*.required' => 'وارد کردن تعداد الزامی است.',
            'unit_price.*.required' => 'وارد کردن فی واحد الزامی است.',
        ]);

        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'invoice_number' => 'INV-' . time(),
            'sale_date' => $request->sale_date ?? now(),
            'remarks' => $request->remarks,
        ]);

        foreach($request->pot_type as $i => $potType) {
            $qty = floatval(persianToEnglishNumber($request->quantity[$i] ?? 0));
            $price = floatval(persianToEnglishNumber($request->unit_price[$i] ?? 0));
            $total = $qty * $price;

            $potNumber = ($potType === 'کرایی') ? null : ($request->pot_number[$i] ?? null);
            $pot_design = $request->pot_design[$i];
            $remarks = $request->remarksforsale[$i] ?? null;

            $sale->items()->create([
                'pot_type' => $potType,
                'pot_number' => $potNumber,
                'pot_design' =>$pot_design,
                'quantity' => $qty,
                'unit_price' => $price,
                'total_price' => $total,
                'remarks' => $remarks,
            ]);
        }

        return redirect()->route('sale.invoice', $sale->id); 
    }

    public function edit(Sale $sale)
    {
        $sale->load('items', 'customer');
        $potTypes = PotType::with('potNumbers')->get();
        $design = Design::all();
        return view('backend.sale.edit', compact('sale','potTypes','design'));
    }

    public function update(Request $request, Sale $sale)
{
    // تبدیل اعداد فارسی به انگلیسی
    $request->merge([
        'quantity' => array_map('persianToEnglishNumber', $request->quantity ?? []),
        'unit_price' => array_map('persianToEnglishNumber', $request->unit_price ?? []),
    ]);

    // اعتبارسنجی
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'pot_type.*' => 'required|string',
        'quantity.*' => 'required|numeric|min:0',
        'unit_price.*' => 'required|numeric|min:0',
    ],[
        'customer_id.required' => 'انتخاب مشتری الزامی است.',
        'pot_type.*.required' => 'وارد کردن نوع دیگ الزامی است.',
        'quantity.*.required' => 'وارد کردن تعداد الزامی است.',
        'unit_price.*.required' => 'وارد کردن فی واحد الزامی است.',
    ]);

    // بروزرسانی اطلاعات فروش
    $sale->update([
        'customer_id' => $request->customer_id,
        'sale_date' => $request->sale_date ?? now(),
        'remarks' => $request->remarks,
    ]);

    // حذف آیتم‌های قبلی
    $sale->items()->delete();

    // اضافه کردن آیتم‌های جدید
    foreach($request->pot_type as $i => $potType) {
        $qty = floatval($request->quantity[$i] ?? 0);
        $price = floatval($request->unit_price[$i] ?? 0);
        $total = $qty * $price;
        $pot_design = $request->pot_design[$i];
        $potNumber = ($potType === 'کرایی') ? null : ($request->pot_number[$i] ?? null);
        $remarks = $request->remarksforsale[$i] ?? null;

        $sale->items()->create([
            'pot_type' => $potType,
            'pot_number' => $potNumber,
            'pot_design' =>$pot_design,
            'quantity' => $qty,
            'unit_price' => $price,
            'total_price' => $total,
            'remarks' => $remarks,
        ]);
    }

    return redirect()->route('sale.invoice', $sale->id);
}


    public function destroy(Sale $sale)
    {
        $sale->items()->delete();
        $sale->payments()->delete();
        $sale->delete();
        return response()->json(['success' => true]);
    }





    public function invoice($sale_id)
    {
        $sale = Sale::with('customer', 'items')->findOrFail($sale_id);

        return view('backend.sale.invoice', compact('sale'));
    }




    

}
