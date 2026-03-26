<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\FactoryExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FactoryExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $expenses = FactoryExpense::orderBy('date', 'desc')->paginate(10);
        return view('backend.factory_expense.index', compact('expenses'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.factory_expense.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // اعتبارسنجی داده‌ها
        $request->validate([
            'expense_type' => 'required',
            'date' => 'required|date',
            'price' => 'required|numeric',
            'notes' => 'nullable|string',
        ], [
            'expense_type.required' => 'انتخاب نوع مصرف الزامی است.',
            'date.required' => 'وارد کردن تاریخ الزامی است.',
            'date.date' => 'تاریخ وارد شده معتبر نیست.',
            'price.required' => 'وارد کردن قیمت الزامی است.',
            'price.numeric' => 'قیمت باید به صورت عدد وارد شود.',
            'notes.string' => 'یادداشت باید متن باشد.'
        ]);

        // تبدیل اعداد فارسی به انگلیسی
        $price = persianToEnglishNumber($request->price);

        // ذخیره رکورد در دیتابیس
        FactoryExpense::create([
            'expense_type' => $request->expense_type,
            'date' => $request->date,
            'price' => $price,
            'notes' => $request->notes,
        ]);

        // پیام موفقیت و ریدایرکت
        Session::flash('success', 'مصرف کارخانه با موفقیت ثبت شد.');
        return redirect()->route('factory_expenses.index');
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
    public function edit($id)
    {
        $expense = FactoryExpense::findOrFail($id);
        return view('backend.factory_expense.edit', compact('expense'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $expense = FactoryExpense::findOrFail($id);

        $request->validate([
            'expense_type' => 'required',
            'date' => 'required|date',
            'price' => 'required|numeric',
            'notes' => 'nullable|string',
        ], [
            'expense_type.required' => 'انتخاب نوع مصرف الزامی است.',
            'date.required' => 'وارد کردن تاریخ الزامی است.',
            'date.date' => 'تاریخ وارد شده معتبر نیست.',
            'price.required' => 'وارد کردن قیمت الزامی است.',
            'price.numeric' => 'قیمت باید به صورت عدد وارد شود.',
            'notes.string' => 'یادداشت باید متن باشد.'
        ]);

        // تبدیل اعداد فارسی به انگلیسی
        $price = persianToEnglishNumber($request->price);

        $expense->update([
            'expense_type' => $request->expense_type,
            'date' => $request->date,
            'price' => $price,
            'notes' => $request->notes,
        ]);

        // پیام موفقیت و ریدایرکت
        Session::flash('success', 'مصرف کارخانه با موفقیت بروزرسانی شد.');
        return redirect()->route('factory_expenses.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $expense = FactoryExpense::findOrFail($id);
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'مصرف کارخانه با موفقیت حذف شد.'
        ]);
    }

}
