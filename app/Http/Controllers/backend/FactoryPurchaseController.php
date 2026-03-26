<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\FactoryPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Morilog\Jalali\Jalalian;

class FactoryPurchaseController extends Controller
{
    /**
     * نمایش لیست خریدهای فابریکه
     */
    public function index()
    {
        $purchases = FactoryPurchase::with('payments')->orderBy('purchase_date', 'desc')->paginate(10);
        return view('backend.factory_purchase.index', compact('purchases'));
    }

    public function paymentsPage($purchaseId)
    {
        $purchase = FactoryPurchase::with('payments')->findOrFail($purchaseId);
        return view('backend.factory_purchase.payments', compact('purchase'));
    }


    /**
     * فرم ایجاد خرید جدید
     */
    public function create()
    {
        return view('backend.factory_purchase.create');
    }

    /**
     * ذخیره خرید جدید
     */
    public function store(Request $request)
    {
        // تبدیل اعداد فارسی به انگلیسی
        $request->merge([
            'quantity'    => persianToEnglishNumber($request->quantity),
            'waste'       => persianToEnglishNumber($request->waste ?? 0),
            'net_weight'  => persianToEnglishNumber($request->net_weight ?? 0),
            'price_per_unit' => persianToEnglishNumber($request->price_per_unit),
        ]);

        // اعتبارسنجی با پیام‌های فارسی
        $request->validate([
            'name'          => 'required|string|max:255',
            'f_name'        => 'required|string|max:255',
            'category'      => 'required|in:soft,hard',
            'purchase_date' => 'required|date',
            'quantity'      => 'required|numeric|min:0.01',
            'price_per_unit'=> 'required|numeric|min:0.01',
        ], [
            'name.required'          => 'وارد کردن نام الزامی است.',
            'f_name.required'        => 'وارد کردن نام پدر الزامی است.',
            'category.required'      => 'انتخاب دسته‌بندی الزامی است.',
            'category.in'            => 'دسته‌بندی معتبر نیست.',
            'purchase_date.required' => 'تاریخ خرید الزامی است.',
            'purchase_date.date'     => 'فرمت تاریخ معتبر نیست.',
            'quantity.required'      => 'مقدار خرید الزامی است.',
            'quantity.numeric'       => 'مقدار خرید باید عدد باشد.',
            'quantity.min'           => 'مقدار خرید باید بیشتر از صفر باشد.',
            'price_per_unit.required'=> 'قیمت فی واحد الزامی است.',
            'price_per_unit.numeric' => 'قیمت فی واحد باید عدد باشد.',
            'price_per_unit.min'     => 'قیمت فی واحد باید بیشتر از صفر باشد.',
        ]);

        // محاسبه جمع کل
        $totalPrice = ($request->quantity - ($request->waste ?? 0)) * $request->price_per_unit;

        FactoryPurchase::create([
            'name'          => $request->name,
            'f_name'        => $request->f_name,
            'category'      => $request->category,
            'purchase_date' => $request->purchase_date,
            'quantity'      => $request->quantity,
            'waste'         => $request->waste ?? 0,
            'net_weight'    => $request->net_weight ?? 0,
            'price_per_unit'=> $request->price_per_unit,
            'total_price'   => $totalPrice,
            'note'          => $request->note ?? null,
        ]);

        Session::flash('success', 'خرید با موفقیت ثبت شد.');
        return redirect()->route('factory-purchases.index');
    }

    /**
     * فرم ویرایش خرید
     */
    public function edit(FactoryPurchase $purchase)
    {
        return view('backend.factory_purchase.edit', compact('purchase'));
    }

    /**
     * بروزرسانی خرید
     */
   public function update(Request $request, FactoryPurchase $Purchase)
{
    // تبدیل اعداد فارسی به انگلیسی
    $quantity = persianToEnglishNumber($request->quantity);
    $waste    = persianToEnglishNumber($request->waste ?? 0);
    $unitPrice= persianToEnglishNumber($request->price_per_unit);

    // اعتبارسنجی
    $request->validate([
        'name'          => 'required|string|max:255',
        'f_name'        => 'required|string|max:255',
        'category'      => 'required|in:soft,hard',
        'purchase_date' => 'required|date',
        'quantity'      => 'required|numeric|min:0.01',
        'price_per_unit'=> 'required|numeric|min:0.01',
    ], [
        'name.required'          => 'وارد کردن نام الزامی است.',
        'f_name.required'        => 'وارد کردن نام پدر الزامی است.',
        'category.required'      => 'انتخاب دسته‌بندی الزامی است.',
        'category.in'            => 'دسته‌بندی معتبر نیست.',
        'purchase_date.required' => 'تاریخ خرید الزامی است.',
        'purchase_date.date'     => 'فرمت تاریخ معتبر نیست.',
        'quantity.required'      => 'مقدار خرید الزامی است.',
        'quantity.numeric'       => 'مقدار خرید باید عدد باشد.',
        'quantity.min'           => 'مقدار خرید باید بیشتر از صفر باشد.',
        'price_per_unit.required'=> 'قیمت فی واحد الزامی است.',
        'price_per_unit.numeric' => 'قیمت فی واحد باید عدد باشد.',
        'price_per_unit.min'     => 'قیمت فی واحد باید بیشتر از صفر باشد.',
    ]);

    // محاسبه سروری
    $netWeight  = max($quantity - $waste, 0);
    $totalPrice = $netWeight * $unitPrice;

    // ذخیره در دیتابیس
    $Purchase->update([
        'name'          => $request->name,
        'f_name'        => $request->f_name,
        'category'      => $request->category,
        'purchase_date' => $request->purchase_date,
        'quantity'      => $quantity,
        'waste'         => $waste,
        'net_weight'    => $netWeight,
        'price_per_unit'=> $unitPrice,
        'total_price'   => $totalPrice,
        'note'          => $request->note ?? null,
    ]);

    return redirect()->route('factory-purchases.index')->with('success', 'اطلاعات خرید با موفقیت بروزرسانی شد.');
}


    /**
     * حذف خرید
     */
    public function destroy(FactoryPurchase $Purchase)
    {
        $Purchase->delete();

        return response()->json([
            'success' => true,
            'message' => 'خرید با موفقیت حذف شد.',
        ]);
    }
}
