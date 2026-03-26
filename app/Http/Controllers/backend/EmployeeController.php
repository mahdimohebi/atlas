<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Employee::with(['contracts', 'salaries', 'pouringPots', 'designPots']);

    if ($request->has('contract_type')) {
        $query->where('contract_type', $request->contract_type);
    }

    $employees = $query->orderBy('id', 'desc')->simplePaginate(10);

    foreach($employees as $emp){
        $totalPaid = $emp->salaries->where('status', 1)->sum('amount');

        if($emp->contract_type == 'ejaraei' && $emp->contracts->count() > 0){

            if($emp->contracts->first()->pricing_type == 'per_kg'){
                $pricePerKg = $emp->contracts->first()->price_per_kg ?? 0;

                if($emp->job_position == 'raikhtgar'){ // ریخت‌گر
                    $totalAmount = $emp->pouringPots->sum('total_weight') * $pricePerKg;
                } else { // دیزاینر
                    $totalAmount = $emp->designPots->sum('quantity') * $pricePerKg;
                }

            }else{
                $pricePeritem = $emp->contracts->first()->price_per_item ?? 0;

                if($emp->job_position == 'raikhtgar'){ // ریخت‌گر
                    $totalAmount = $emp->pouringPots->sum('quantity') * $pricePeritem;
                } else { // دیزاینر
                    $totalAmount = $emp->designPots->sum('quantity') * $pricePeritem;
                }
            }
            

        

        } else {
            // روزمزد یا پیش‌فرض
            $totalAmount = $emp->salaries->sum('amount');
        }

        $emp->total_amount = $totalAmount;
        $emp->total_paid = $totalPaid;
        $emp->remaining = $totalAmount - $totalPaid;
    }

    return view('backend.employee.index', compact('employees'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.employee.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'tazkira_no' => 'required|string|max:50',
            'job_position' => 'required|in:raikhtgar,designer',
            'contract_type' => 'required|in:ejaraei,rozmozd'
        ], [
            'name.required' => 'وارد کردن نام الزامی است.',
            'father_name.required' => 'وارد کردن نام پدر الزامی است.',
            'address.required' => 'وارد کردن آدرس الزامی است.',
            'phone.required' => 'وارد کردن شماره تلفون الزامی است.',
            'tazkira_no.required' => 'وارد کردن شماره تذکره الزامی است.',
            'contract_type.required' => 'انتخاب نوع قرارداد الزامی است.',
            'contract_type.in' => 'نوع قرارداد باید اجاره‌ای یا روزمزد باشد.',
            'job_position.required' => 'وارد کردن شغل الزامی است.',
            'job_position.in' => 'شغل باید ریخت گر یا دیزاینر باشد.',
        ]);

        Employee::create([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'address' => $request->address,
            'phone' => persianToEnglishNumber($request->phone),
            'tazkira_no' => persianToEnglishNumber($request->tazkira_no),
            'job_position' => $request->job_position,
            'contract_type' => $request->contract_type,
            'is_active' => false, // پیش‌فرض غیر فعال
        ]);

        Session::flash('success', 'کارمند با موفقیت ثبت شد.');
        return redirect()->route('employee.index', ['contract_type' => $request->contract_type]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $contracts = $employee->contracts()->latest()->get();
         return view('backend.contract.index', compact('employee', 'contracts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return view('backend.employee.edit')->with('employee',$employee);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Employee $employee)
    {
        // اعتبارسنجی
        $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'tazkira_no' => 'required|string|max:50',
            'job_position' => 'required|in:raikhtgar,designer',
            'contract_type' => 'required|in:ejaraei,rozmozd'
        ], [
            'name.required' => 'وارد کردن نام الزامی است.',
            'father_name.required' => 'وارد کردن نام پدر الزامی است.',
            'address.required' => 'وارد کردن آدرس الزامی است.',
            'phone.required' => 'وارد کردن شماره تلفون الزامی است.',
            'tazkira_no.required' => 'وارد کردن شماره تذکره الزامی است.',
            'contract_type.required' => 'انتخاب نوع قرارداد الزامی است.',
            'contract_type.in' => 'نوع قرارداد باید اجاره‌ای یا روزمزد باشد.',
            'job_position.required' => 'وارد کردن شغل الزامی است.',
            'job_position.in' => 'شغل باید ریخت گر یا دیزاینر باشد.',
        ]);

        // بروزرسانی اطلاعات کارمند
        $employee->update([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'address' => $request->address,
            'phone' => persianToEnglishNumber($request->phone),
            'tazkira_no' => persianToEnglishNumber($request->tazkira_no),
            'job_position' => $request->job_position,
            'contract_type' => $request->contract_type,
            'is_active' => $request->has('is_active') ? true : false, // مدیریت وضعیت فعال/غیرفعال
        ]);

        Session::flash('success', 'کارمند با موفقیت ویرایش شد.');
        return redirect()->route('employee.index', ['contract_type' => $request->contract_type]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $contract_type = $employee->contract_type;
        $employee->delete();
        return response()->json([
            'success' => true,
            'message' => 'تراکنش با موفقیت حذف شد.',
            'redirect' => route('employee.index', ['contract_type' => $contract_type])
        ]);
    }
    // ======================== live search ==============================
 public function search(Request $request)
    {
        $query = $request->get('query');
        $contractType = $request->get('contract_type'); // گرفتن نوع قرارداد

        $employees = Employee::query();

        // اگر نوع قرارداد ارسال شده بود، فیلتر شود
        if (!empty($contractType)) {
            $employees->where('contract_type', $contractType);
        }

        // اگر سرچ هم انجام شد
        if (!empty($query)) {
            $employees->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                ->orWhere('father_name', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->orWhere('tazkira_no', 'like', "%{$query}%");
            });
        }

        $employees = $employees->orderBy('id', 'desc')->paginate(10);

        if ($request->ajax()) {
            $table = view('backend.employee.table', compact('employees'))->render();
            $pagination = view('backend.employee.pagination', compact('employees'))->render();
            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('backend.employee.index', compact('employees'));
    }

}
