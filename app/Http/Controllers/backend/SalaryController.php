<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Salary::query()->with('employee');

        // اگر پارامتر employee_id وجود داشت، فقط معاش‌های همان کارمند را نمایش بده
        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
            $employee = Employee::find($request->employee_id); // کارمند مورد نظر
        } 
        $salaries = $query->paginate(10);

        return view('backend.salary.index', compact('salaries', 'employee'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create_salary($employee_id)
    {
        
        $employee = Employee::findOrFail($employee_id);
        return view('backend.salary.create', compact('employee'));
    }

    public function create()
    {
        //
    }
        

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // تبدیل اعداد فارسی به انگلیسی
        $request->merge([
            'amount' => persianToEnglishNumber($request->amount),
        ]);

        // اعتبارسنجی
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:ejaraei,rozmozd',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'status' => 'required'
        ], [
            'employee_id.required' => 'کارمند انتخاب نشده است.',
            'employee_id.exists' => 'کارمند انتخاب شده معتبر نیست.',
            'type.required' => 'نوع معاش الزامی است.',
            'type.in' => 'نوع معاش نامعتبر است.',
            'amount.required' => 'مبلغ معاش الزامی است.',
            'amount.numeric' => 'مبلغ معاش باید عدد باشد.',
            'amount.min' => 'مبلغ معاش نمی‌تواند منفی باشد.',
            'status.required' => 'وضعیت پرداختی انتخاب نشده است.',

        ]);

        // ذخیره در دیتابیس
        $salary = new Salary();
        $salary->employee_id = $request->employee_id;
        $salary->type = $request->type;
        $salary->amount = $request->amount;
        $salary->date = $request->date;
        $salary->notes = $request->notes;
        $salary->status = $request->status;
        $salary->save();

        // پیام موفقیت و بازگشت به صفحه کارمند
        Session::flash('success', 'معاش با موفقیت ثبت شد.');
        return redirect()->route('salary.index', ['employee_id' => $request->employee_id]);
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
        $salary = Salary::with('employee')->findOrFail($id); // معاش را همراه با کارمند بگیر
        $employee = $salary->employee; // کارمند مربوطه

        return view('backend.salary.edit', compact('salary', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $salary = Salary::findOrFail($id);

        $request->merge([
            'amount' => persianToEnglishNumber($request->amount),
        ]);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:ejaraei,rozmozd',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'date' => 'nullable|date',
            'status' => 'required'
        ], [
            'employee_id.required' => 'کارمند انتخاب نشده است.',
            'employee_id.exists' => 'کارمند انتخاب شده معتبر نیست.',
            'type.required' => 'نوع معاش الزامی است.',
            'type.in' => 'نوع معاش نامعتبر است.',
            'amount.required' => 'مبلغ معاش الزامی است.',
            'amount.numeric' => 'مبلغ معاش باید عدد باشد.',
            'amount.min' => 'مبلغ معاش نمی‌تواند منفی باشد.',
            'status.required' => 'وضعیت پرداختی انتخاب نشده است.',
        ]);

        $salary->employee_id = $request->employee_id;
        $salary->type = $request->type;
        $salary->amount = $request->amount;
        $salary->date = $request->date;
        $salary->notes = $request->notes;
        $salary->status = $request->status;
        $salary->save();

        Session::flash('success', 'معاش با موفقیت بروزرسانی شد.');
        return redirect()->route('salary.index', ['employee_id' => $request->employee_id]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return response()->json([
            'success' => true,
            'message' => 'معاش با موفقیت حذف شد.'
        ]);
    }
}
