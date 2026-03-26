<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // تبدیل اعداد فارسی به انگلیسی
    $request->merge([
        'duration' => persianToEnglishNumber($request->duration),
        'price_per_kg' => persianToEnglishNumber($request->price_per_kg),
        'price_per_item' => persianToEnglishNumber($request->price_per_item),
        'amount' => persianToEnglishNumber($request->amount),
        'person_tazkira_no' => persianToEnglishNumber($request->person_tazkira_no),
    ]);

    // Validation
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'duration' => 'required|numeric',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',

        // بخش جدید
        'pricing_type' => 'required|in:per_item,per_kg',
        'price_per_item' => 'required_if:pricing_type,per_item|nullable|numeric|min:0',
        'price_per_kg' => 'required_if:pricing_type,per_kg|nullable|numeric|min:0',

        'contract_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'guarantee_type' => 'required',

        'amount' => 'required_if:guarantee_type,naqdi|nullable|numeric|min:0',

        'person_name' => 'required_if:guarantee_type,shakhs|nullable|string|max:255',
        'person_father_name' => 'required_if:guarantee_type,shakhs|nullable|string|max:255',
        'person_tazkira_no' => 'required_if:guarantee_type,shakhs|nullable|string|max:50',
        'person_address' => 'required_if:guarantee_type,shakhs|nullable|string|max:255',
        'person_phone' => 'required_if:guarantee_type,shakhs|nullable|string|max:20',
        'person_photo' => 'required_if:guarantee_type,shakhs|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $contract = new Contract();
    $contract->employee_id = $request->employee_id;
    $contract->duration = $request->duration;
    $contract->start_date = $request->start_date;
    $contract->end_date = $request->end_date;

    //  ذخیره نوع قیمت
    $contract->pricing_type = $request->pricing_type;

    //  تمیز نگه داشتن دیتابیس (فقط یکی ذخیره شود)
    if ($request->pricing_type === 'per_item') {
        $contract->price_per_item = $request->price_per_item;
        $contract->price_per_kg = null;
    } else {
        $contract->price_per_kg = $request->price_per_kg;
        $contract->price_per_item = null;
    }

    $contract->guarantee_type = $request->guarantee_type;

    // آپلود عکس قرارداد
    if ($request->hasFile('contract_photo')) {
        $file = $request->file('contract_photo');
        $filename = time() . '_contract.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/contracts'), $filename);
        $contract->contract_photo = 'uploads/contracts/' . $filename;
    }

    $contract->save();

    // ذخیره ضمانت
    if ($request->guarantee_type === 'naqdi') {
        $contract->guarantee()->create([
            'guarantee_type' => 'naqdi',
            'amount' => $request->amount,
        ]);
    } elseif ($request->guarantee_type === 'shakhs') {
        $personData = [
            'guarantee_type' => 'shakhs',
            'name' => $request->person_name,
            'father_name' => $request->person_father_name,
            'tazkira_no' => $request->person_tazkira_no,
            'address' => $request->person_address,
            'phone' => $request->person_phone,
        ];

        if ($request->hasFile('person_photo')) {
            $file = $request->file('person_photo');
            $filename = time() . '_person.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/guarantees'), $filename);
            $personData['photo'] = 'uploads/guarantees/' . $filename;
        }

        $contract->guarantee()->create($personData);
    }

    Session::flash('success', 'قرارداد با موفقیت ثبت شد.');
    return redirect()->route('employee.index', ['contract_type' => 'ejaraei']);
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contract = Contract::with('employee', 'guarantee')->findOrFail($id);

        // برگشت به صورت view فقط محتوای مودال
        return view('backend.contract.modal_content', compact('contract'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $employee = $contract->employee;
        return view('backend.contract.edit', compact('contract', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Contract $contract)
{
    // تبدیل اعداد فارسی به انگلیسی
    $request->merge([
        'duration' => persianToEnglishNumber($request->duration),
        'price_per_kg' => persianToEnglishNumber($request->price_per_kg),
        'price_per_item' => persianToEnglishNumber($request->price_per_item),
        'amount' => persianToEnglishNumber($request->amount),
        'person_tazkira_no' => persianToEnglishNumber($request->person_tazkira_no),
        'person_phone' => persianToEnglishNumber($request->person_phone),
    ]);

    // اعتبارسنجی
$request->validate([
    'duration' => 'required|numeric|min:0',
    'start_date' => 'required|date',
    'end_date' => 'required|date|after_or_equal:start_date',
    'pricing_type' => 'required|in:per_item,per_kg',
    'price_per_item' => 'nullable|numeric|min:0',
    'price_per_kg' => 'nullable|numeric|min:0',
    'contract_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    'guarantee_type' => 'required|in:naqdi,shakhs',
    'amount' => 'required_if:guarantee_type,naqdi|nullable|numeric|min:0',

    'person_name' => 'required_if:guarantee_type,shakhs|nullable|string|max:255',
    'person_father_name' => 'required_if:guarantee_type,shakhs|nullable|string|max:255',
    'person_tazkira_no' => 'required_if:guarantee_type,shakhs|nullable|string|max:50',
    'person_address' => 'required_if:guarantee_type,shakhs|nullable|string|max:255',
    'person_phone' => 'required_if:guarantee_type,shakhs|nullable|string|max:20',
    'person_photo' => 'required_if:guarantee_type,shakhs|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
], [
    'duration.required' => 'لطفاً مدت قرارداد را وارد کنید.',
    'duration.numeric' => 'مدت قرارداد باید عدد باشد.',
    'duration.min' => 'مدت قرارداد نمی‌تواند منفی باشد.',

    'start_date.required' => 'لطفاً تاریخ شروع قرارداد را وارد کنید.',
    'start_date.date' => 'تاریخ شروع نامعتبر است.',

    'end_date.required' => 'لطفاً تاریخ پایان قرارداد را وارد کنید.',
    'end_date.date' => 'تاریخ پایان نامعتبر است.',
    'end_date.after_or_equal' => 'تاریخ پایان باید برابر یا بعد از تاریخ شروع باشد.',

    'pricing_type.required' => 'لطفاً نوع قیمت را انتخاب کنید.',
    'pricing_type.in' => 'نوع قیمت انتخاب شده معتبر نیست.',

    'price_per_item.numeric' => 'قیمت فی دانه باید عدد باشد.',
    'price_per_item.min' => 'قیمت فی دانه نمی‌تواند منفی باشد.',

    'price_per_kg.numeric' => 'قیمت فی کیلو باید عدد باشد.',
    'price_per_kg.min' => 'قیمت فی کیلو نمی‌تواند منفی باشد.',

    'contract_photo.image' => 'عکس قرارداد باید یک فایل تصویری باشد.',
    'contract_photo.mimes' => 'فرمت عکس قرارداد باید jpeg, png, jpg یا gif باشد.',
    'contract_photo.max' => 'حجم عکس قرارداد نباید بیشتر از 2MB باشد.',

    'guarantee_type.required' => 'لطفاً نوع ضمانت را انتخاب کنید.',
    'guarantee_type.in' => 'نوع ضمانت انتخاب شده معتبر نیست.',

    'amount.required_if' => 'لطفاً مقدار نقدی را وارد کنید.',
    'amount.numeric' => 'مقدار نقدی باید عدد باشد.',
    'amount.min' => 'مقدار نقدی نمی‌تواند منفی باشد.',

    'person_name.required_if' => 'لطفاً نام شخص را وارد کنید.',
    'person_name.string' => 'نام شخص باید متن باشد.',
    'person_name.max' => 'نام شخص نمی‌تواند بیشتر از 255 کاراکتر باشد.',

    'person_father_name.required_if' => 'لطفاً نام پدر را وارد کنید.',
    'person_father_name.string' => 'نام پدر باید متن باشد.',
    'person_father_name.max' => 'نام پدر نمی‌تواند بیشتر از 255 کاراکتر باشد.',

    'person_tazkira_no.required_if' => 'لطفاً شماره تذکره را وارد کنید.',
    'person_tazkira_no.string' => 'شماره تذکره باید متن باشد.',
    'person_tazkira_no.max' => 'شماره تذکره نمی‌تواند بیشتر از 50 کاراکتر باشد.',

    'person_address.required_if' => 'لطفاً آدرس شخص را وارد کنید.',
    'person_address.string' => 'آدرس باید متن باشد.',
    'person_address.max' => 'آدرس نمی‌تواند بیشتر از 255 کاراکتر باشد.',

    'person_phone.required_if' => 'لطفاً شماره تلفن را وارد کنید.',
    'person_phone.string' => 'شماره تلفن باید متن باشد.',
    'person_phone.max' => 'شماره تلفن نمی‌تواند بیشتر از 20 کاراکتر باشد.',

    'person_photo.image' => 'عکس تعهدنامه باید یک فایل تصویری باشد.',
    'person_photo.mimes' => 'فرمت عکس تعهدنامه باید jpeg, png, jpg یا gif باشد.',
    'person_photo.max' => 'حجم عکس تعهدنامه نباید بیشتر از 2MB باشد.',
]);

    // آپدیت اطلاعات قرارداد
    $contract->update([
        'duration' => $request->duration,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'pricing_type' => $request->pricing_type,
        'price_per_item' => $request->pricing_type === 'per_item' ? $request->price_per_item : null,
        'price_per_kg' => $request->pricing_type === 'per_kg' ? $request->price_per_kg : null,
        'guarantee_type' => $request->guarantee_type,
    ]);

    // آپلود عکس قرارداد
    if ($request->hasFile('contract_photo')) {
        if ($contract->contract_photo && file_exists(public_path($contract->contract_photo))) {
            unlink(public_path($contract->contract_photo));
        }
        $file = $request->file('contract_photo');
        $filename = time() . '_contract.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/contracts'), $filename);
        $contract->update(['contract_photo' => 'uploads/contracts/' . $filename]);
    }

    // حذف ضمانت قبلی اگر نوع تغییر کرده باشد
    if ($contract->guarantee && $contract->guarantee->guarantee_type !== $request->guarantee_type) {
        if ($contract->guarantee->photo && file_exists(public_path($contract->guarantee->photo))) {
            unlink(public_path($contract->guarantee->photo));
        }
        $contract->guarantee()->delete();
    }

    // آماده سازی داده‌های ضمانت
    if ($request->guarantee_type === 'naqdi') {
        $guaranteeData = [
            'guarantee_type' => 'naqdi',
            'amount' => $request->amount,
            'name' => null,
            'father_name' => null,
            'tazkira_no' => null,
            'address' => null,
            'phone' => null,
            'photo' => null,
        ];
    } else {
        $guaranteeData = [
            'guarantee_type' => 'shakhs',
            'name' => $request->person_name,
            'father_name' => $request->person_father_name,
            'tazkira_no' => $request->person_tazkira_no,
            'address' => $request->person_address,
            'phone' => $request->person_phone,
            'photo' => $contract->guarantee->photo ?? null,
        ];

        if ($request->hasFile('person_photo')) {
            if (isset($contract->guarantee->photo) && file_exists(public_path($contract->guarantee->photo))) {
                unlink(public_path($contract->guarantee->photo));
            }
            $file = $request->file('person_photo');
            $filename = time() . '_person.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/guarantees'), $filename);
            $guaranteeData['photo'] = 'uploads/guarantees/' . $filename;
        }
    }

    // ایجاد یا آپدیت ضمانت
    $contract->guarantee()->updateOrCreate(
        ['contract_id' => $contract->id],
        $guaranteeData
    );

    Session::flash('success', 'قرارداد با موفقیت بروزرسانی شد.');
    return redirect()->route('employee.show', $contract->employee_id);
}





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();
        return response()->json([
            'success' => true,
            'message' => 'قرارداد با موفقیت حذف شد.'
        ]);
    }


    public function createForEmployee($employee_id)
    {
        $employee = Employee::where('id', $employee_id)
                            ->where('contract_type', 'ejaraei')
                            ->firstOrFail();
        return view('backend.contract.create', compact('employee'));
    }


    
}
