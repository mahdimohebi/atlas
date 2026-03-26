<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PotType;
use App\Models\PouringPot;
use App\Models\FactoryPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PouringpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pots = PouringPot::with('employee')->paginate(10);
        return view('backend.pouringpot.index', compact('pots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // گرفتن تمام کارمندان ریخت‌گر و فعال
        $employees = Employee::where('job_position', 'raikhtgar')
                                        ->where('is_active', true) // فقط کارمندان فعال
                                        ->get();
        $potTypes = PotType::with('potNumbers.potSubtypes')->get();

        return view('backend.pouringpot.create', compact('employees','potTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // تبدیل اعداد فارسی به انگلیسی
    $request->merge([
        'weight_per_pot' => persianToEnglishNumber($request->weight_per_pot),
        'quantity' => persianToEnglishNumber($request->quantity),
        'price_per_pot' => persianToEnglishNumber($request->price_per_pot),
    ]);

    $weightPerPot = (float) $request->weight_per_pot;
    $quantity = (int) $request->quantity;
    $totalWeight = $weightPerPot * $quantity;

    // اعتبارسنجی با پیام فارسی
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'date' => 'required|date',
        'pot_type' => 'required|string',
        'weight_per_pot' => 'required|numeric|min:0',
        'quantity' => 'required|numeric|min:1',
        'price_per_pot' => 'required|numeric|min:0',
    ], [
        'employee_id.required' => 'انتخاب کارمند الزامی است.',
        'employee_id.exists' => 'این کارمند در سیستم موجود نیست.',
        'date.required' => 'تاریخ الزامی است.',
        'date.date' => 'تاریخ نامعتبر است.',
        'pot_type.required' => 'نوع دیگ الزامی است.',
        'weight_per_pot.required' => 'وزن فی دیگ الزامی است.',
        'weight_per_pot.numeric' => 'وزن فی دیگ باید عدد باشد.',
        'weight_per_pot.min' => 'وزن فی دیگ باید حداقل 0 باشد.',
        'quantity.required' => 'تعداد دیگ الزامی است.',
        'quantity.numeric' => 'تعداد دیگ باید عدد باشد.',
        'quantity.min' => 'تعداد دیگ باید حداقل 1 باشد.',
        'price_per_pot.required' => 'فی دیگ الزامی است.',
        'price_per_pot.numeric' => 'فی دیگ باید عدد باشد.',
        'price_per_pot.min' => 'فی دیگ باید حداقل 0 باشد.',
    ]);

    // گرفتن قرارداد کارمند
    $employee = Employee::findOrFail($request->employee_id);
    $contract = $employee->contracts()->latest()->first(); // فرض بر این است که رابطه contract تعریف شده


    if (!$contract) {
        return redirect()->back()->withErrors(['employee_id' => 'این کارمند قرارداد ندارد.'])->withInput();
    }

            // مجموع وزن قبلی ثبت شده در تمام ریخت‌ها
        $usedWeight = PouringPot::sum('total_weight');

        // موجودی کل کارخانه (فرضاً از آخرین FactoryPurchase)
        $totalFactoryWeight = FactoryPurchase::sum('net_weight');

        $remaining = $totalFactoryWeight - $usedWeight;

        if ($totalWeight > $remaining) {
            return redirect()->back()->withErrors([
                'weight_per_pot' => "وزن وارد شده بیش از باقیمانده موجودی کل کارخانه است. باقیمانده: {$remaining} کیلوگرم"
            ])->withInput();
        }



    // ذخیره ریخت
    $pouring = new PouringPot();
    $pouring->employee_id = $request->employee_id;
    $pouring->date = $request->date;
    $pouring->pot_type = $request->pot_type;
    $pouring->pot_number = $request->pot_number ?? null;
    $pouring->pot_sub_type = $request->pot_sub_type ?? null;
    $pouring->weight_per_pot = $weightPerPot;
    $pouring->quantity = $quantity;
    $pouring->total_weight = $totalWeight;
    $pouring->price_per_pot = $request->price_per_pot;
    $pouring->total_price = $request->price_per_pot * $quantity;
    $pouring->note = $request->note;
    $pouring->save();

    Session::flash('success', 'ریخت با موفقیت ثبت شد.');
    return redirect()->route('pouring_pot.index');

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
    public function edit(string $id)
    {
        // گرفتن تمام کارمندان ریخت‌گر و فعال
        $employees = Employee::where('job_position', 'raikhtgar')
                                        ->where('is_active', true) // فقط کارمندان فعال
                                        ->get();
        $pouring = PouringPot::findOrFail($id);
        $potTypes = PotType::with('potNumbers.potSubtypes')->get();
        

        return view('backend.pouringpot.edit', compact('employees','pouring','potTypes'));
    }

    /**
     * Update the specified resource in storage.
     */

public function update(Request $request, $id)
{
    // تبدیل اعداد فارسی به انگلیسی
    $request->merge([
        'weight_per_pot' => persianToEnglishNumber($request->weight_per_pot),
        'quantity' => persianToEnglishNumber($request->quantity),
        'price_per_pot' => persianToEnglishNumber($request->price_per_pot),
    ]);

    // تبدیل به نوع مناسب
    $weightPerPot = (float) $request->weight_per_pot;
    $quantity = (int) $request->quantity;
    $pricePerPot = (float) $request->price_per_pot;
    $totalWeight = $weightPerPot * $quantity;
    $totalPrice = $pricePerPot * $quantity;

    // اعتبارسنجی با پیام فارسی
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'date' => 'required|date',
        'pot_type' => 'required|string',
        'weight_per_pot' => 'required|numeric|min:0',
        'quantity' => 'required|numeric|min:1',
        'price_per_pot' => 'required|numeric|min:0',
    ], [
        'employee_id.required' => 'انتخاب کارمند الزامی است.',
        'employee_id.exists' => 'این کارمند در سیستم موجود نیست.',
        'date.required' => 'تاریخ الزامی است.',
        'date.date' => 'تاریخ نامعتبر است.',
        'pot_type.required' => 'نوع دیگ الزامی است.',
        'weight_per_pot.required' => 'وزن فی دیگ الزامی است.',
        'weight_per_pot.numeric' => 'وزن فی دیگ باید عدد باشد.',
        'weight_per_pot.min' => 'وزن فی دیگ باید حداقل 0 باشد.',
        'quantity.required' => 'تعداد دیگ الزامی است.',
        'quantity.numeric' => 'تعداد دیگ باید عدد باشد.',
        'quantity.min' => 'تعداد دیگ باید حداقل 1 باشد.',
        'price_per_pot.required' => 'فی دیگ الزامی است.',
        'price_per_pot.numeric' => 'فی دیگ باید عدد باشد.',
        'price_per_pot.min' => 'فی دیگ باید حداقل 0 باشد.',
    ]);

    // گرفتن ریخت موردنظر برای ویرایش
    $pouring = PouringPot::findOrFail($id);

    // گرفتن آخرین قرارداد کارمند
    $employee = Employee::findOrFail($request->employee_id);
    $contract = $employee->contracts()->latest()->first();

    if (!$contract) {
        return redirect()->back()->withErrors(['employee_id' => 'این کارمند قرارداد ندارد.'])->withInput();
    }

            // مجموع وزن قبلی ثبت شده در تمام ریخت‌ها
        $usedWeight = PouringPot::sum('total_weight');

        // موجودی کل کارخانه (فرضاً از آخرین FactoryPurchase)
        $totalFactoryWeight = FactoryPurchase::sum('net_weight');

        $remaining = $totalFactoryWeight - $usedWeight;

        if ($totalWeight > $remaining) {
            return redirect()->back()->withErrors([
                'weight_per_pot' => "وزن وارد شده بیش از باقیمانده موجودی کل کارخانه است. باقیمانده: {$remaining} کیلوگرم"
            ])->withInput();
        }
        
    // بروزرسانی ریخت با save() برای اطمینان از ذخیره صحیح اعداد
    $pouring->employee_id = $request->employee_id;
    $pouring->date = $request->date;
    $pouring->pot_type = $request->pot_type;
    $pouring->pot_number = $request->pot_number ?? null;
    $pouring->pot_sub_type = $request->pot_sub_type ?? null;
    $pouring->weight_per_pot = $weightPerPot;
    $pouring->quantity = $quantity;
    $pouring->total_weight = $totalWeight;
    $pouring->price_per_pot = $pricePerPot;
    $pouring->total_price = $totalPrice;
    $pouring->note = $request->note;

    $pouring->save();

    Session::flash('success', 'ریخت با موفقیت بروزرسانی شد.');
    return redirect()->route('pouring_pot.index');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PouringPot $pouring_pot)
    {
        $pouring_pot->forceDelete();
        return response()->json([
            'success' => true,
            'message' => 'ریخت با موفقیت حذف شد.'
        ]);
    }


    // ========= live search =========================================


public function search(Request $request)
{
    $query = persianToEnglishNumber($request->get('query'));

    $pots = PouringPot::with('employee')
                ->when($query, function($q) use ($query) {
                    $q->whereDate('date', $query); 
                })
                ->orderBy('date', 'desc')
                ->paginate(10);

    return view('backend.pouringpot.index', compact('pots'));
}













}
