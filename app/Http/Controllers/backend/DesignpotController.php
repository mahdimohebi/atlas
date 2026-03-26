<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\DesignPot;
use App\Models\Employee;
use App\Models\PotType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DesignpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designs = DesignPot::with('employee')->paginate(10);
        return view('backend.designpot.index', compact('designs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // گرفتن تمام کارمندان ریخت‌گر و فعال
        $employees = Employee::where('job_position', 'designer')
                                        ->where('is_active', true) // فقط کارمندان فعال
                                        ->get();
        $potTypes = PotType::with('potNumbers')->get();
        $design = Design::all();

        return view('backend.designpot.create', compact('employees','potTypes','design'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // تبدیل اعداد فارسی به انگلیسی
        $request->merge([
            'quantity' => persianToEnglishNumber($request->quantity),
            'price_per_pot' => persianToEnglishNumber($request->price_per_pot),
        ]);

        $quantity = (int) $request->quantity;
        $pricePerPot = (float) $request->price_per_pot;
        $totalPrice = $quantity * $pricePerPot;

        // اعتبارسنجی با پیام فارسی
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'pot_type' => 'required|string',
            'design_type' => 'required|string',
            'quantity' => 'required|numeric|min:1',
            'price_per_pot' => 'required|numeric|min:0',
        ], [
            'employee_id.required' => 'انتخاب کارمند دیزاینر الزامی است.',
            'employee_id.exists' => 'این کارمند در سیستم موجود نیست.',
            'date.required' => 'تاریخ الزامی است.',
            'date.date' => 'تاریخ نامعتبر است.',
            'pot_type.required' => 'نوع دیگ الزامی است.',
            'design_type.required' => 'نوع دیزاین الزامی است.',
            'quantity.required' => 'تعداد دیگ الزامی است.',
            'quantity.numeric' => 'تعداد دیگ باید عدد باشد.',
            'quantity.min' => 'تعداد دیگ باید حداقل 1 باشد.',
            'price_per_pot.required' => 'فی دیگ الزامی است.',
            'price_per_pot.numeric' => 'فی دیگ باید عدد باشد.',
            'price_per_pot.min' => 'فی دیگ باید حداقل 0 باشد.',
        ]);

        // ذخیره دیزاین
        $design = new DesignPot();
        $design->employee_id = $request->employee_id;
        $design->date = $request->date;
        $design->pot_type = $request->pot_type;
        $design->pot_number = $request->pot_number ?? null;
        $design->design_type = $request->design_type;
        $design->quantity = $quantity;
        $design->price_per_pot = $pricePerPot;
        $design->total_price = $totalPrice;
        $design->note = $request->note;
        $design->save();

        Session::flash('success', 'دیزاین با موفقیت ثبت شد.');
        return redirect()->route('design_pot.index');
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
        $employees = Employee::where('job_position', 'designer')
                                        ->where('is_active', true) // فقط کارمندان فعال
                                        ->get();
        $design = DesignPot::findOrFail($id);
        $potTypes = PotType::with('potNumbers.potSubtypes')->get();
        $design_pot = Design::all();

        return view('backend.designpot.edit', compact('employees','design','potTypes','design_pot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // تبدیل اعداد فارسی به انگلیسی
        $request->merge([
            'quantity' => persianToEnglishNumber($request->quantity),
            'price_per_pot' => persianToEnglishNumber($request->price_per_pot),
        ]);

        $quantity = (int) $request->quantity;
        $pricePerPot = (float) $request->price_per_pot;
        $totalPrice = $quantity * $pricePerPot;

        // اعتبارسنجی
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'pot_type' => 'required|string',
            'quantity' => 'required|numeric|min:1',
            'price_per_pot' => 'required|numeric|min:0',
        ], [
            'employee_id.required' => 'انتخاب کارمند الزامی است.',
            'employee_id.exists' => 'این کارمند در سیستم موجود نیست.',
            'date.required' => 'تاریخ دیزاین الزامی است.',
            'date.date' => 'تاریخ نامعتبر است.',
            'pot_type.required' => 'نوع دیگ الزامی است.',
            'quantity.required' => 'تعداد دیگ الزامی است.',
            'quantity.numeric' => 'تعداد دیگ باید عدد باشد.',
            'quantity.min' => 'تعداد دیگ باید حداقل 1 باشد.',
            'price_per_pot.required' => 'فی دیگ الزامی است.',
            'price_per_pot.numeric' => 'فی دیگ باید عدد باشد.',
            'price_per_pot.min' => 'فی دیگ باید حداقل 0 باشد.',
        ]);

        // پیدا کردن رکورد
        $design = DesignPot::findOrFail($id);

        // آپدیت مقادیر
        $design->employee_id = $request->employee_id;
        $design->date = $request->date;
        $design->pot_type = $request->pot_type;
        $design->pot_number = $request->pot_number ?? null;
        $design->design_type = $request->design_type;
        $design->quantity = $quantity;
        $design->price_per_pot = $pricePerPot;
        $design->total_price = $totalPrice;
        $design->note = $request->note ?? null;

        $design->save();

        Session::flash('success', 'دیزاین با موفقیت بروزرسانی شد.');
        return redirect()->route('design_pot.index');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DesignPot $design_pot)
    {
        $design_pot->delete();
        return response()->json([
            'success' => true,
            'message' => 'دیزاین با موفقیت حذف شد.'
        ]);
    }
}
