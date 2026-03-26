<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AluminumExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AluminumExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index(Request $request)
    {
        $type = $request->type;

        $query = AluminumExpense::query();

        if ($type == 'sell_expense') {
            $query->where('transaction_type', 'sale');
        } elseif ($type == 'purchase_expense') {
            $query->where('transaction_type', 'purchase');
        }

        $expenses = $query->orderBy('date', 'desc')->paginate(10);

        return view('backend.aluminum_expense.index', compact('expenses', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->type;
        // نمایش فرم ثبت مصرف آلومینیوم
        return view('backend.aluminum_expense.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        // اعتبارسنجی با پیام‌های سفارشی
        $request->validate([
            'expense_type' => 'required',
            'date' => 'required|date',
            'price' => 'required|numeric',
            'notes' => 'nullable|string'
        ], [
            'expense_type.required' => 'انتخاب نوع مصرف الزامی است.',
            'date.required' => 'وارد کردن تاریخ الزامی است.',
            'date.date' => 'تاریخ وارد شده معتبر نیست.',
            'price.required' => 'وارد کردن قیمت الزامی است.',
            'price.numeric' => 'قیمت باید به صورت عدد وارد شود.',
            'notes.string' => 'یادداشت باید متن باشد.'
        ]);

        // ایجاد رکورد جدید
        AluminumExpense::create([
            'expense_type' => $request->expense_type,
            'transaction_type' => $request->type == 'sell_expense' ? 'sale' : 'purchase',
            'date' => $request->date,
            'price' => persianToEnglishNumber($request->price),
            'notes' => $request->notes
        ]);

        // نمایش پیام موفقیت و ریدایرکت به صفحه لیست
        Session::flash('success', 'مصرف آلومینیوم با موفقیت ثبت شد.');
        return redirect()->route('aluminum_expenses.index', ['type' => $request->type]);
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
        // پیدا کردن مصرف آلومینیوم بر اساس ID
        $expense = AluminumExpense::findOrFail($id);
        $type = $expense->transaction_type == 'sale' ? 'sell_expense' : 'purchase_expense';
        // نمایش فرم ویرایش و ارسال داده‌ها به Blade
        return view('backend.aluminum_expense.edit', compact('expense','type'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // پیدا کردن رکورد موجود
        $expense = AluminumExpense::findOrFail($id);

        // اعتبارسنجی با پیام‌های سفارشی
        $request->validate([
            'expense_type' => 'required',
            'date' => 'required|date',
            'price' => 'required|numeric',
            'notes' => 'nullable|string'
        ], [
            'expense_type.required' => 'انتخاب نوع مصرف الزامی است.',
            'date.required' => 'وارد کردن تاریخ الزامی است.',
            'date.date' => 'تاریخ وارد شده معتبر نیست.',
            'price.required' => 'وارد کردن قیمت الزامی است.',
            'price.numeric' => 'قیمت باید به صورت عدد وارد شود.',
            'notes.string' => 'یادداشت باید متن باشد.'
        ]);

        // تبدیل اعداد فارسی به انگلیسی قبل از ذخیره
        $price = persianToEnglishNumber($request->price);

        // بروزرسانی رکورد
        $expense->update([
            'expense_type' => $request->expense_type,
            'transaction_type' => $request->type == 'sell_expense' ? 'sale' : 'purchase',
            'date' => $request->date,
            'price' => $price,
            'notes' => $request->notes
        ]);

        // پیام موفقیت و ریدایرکت
        Session::flash('success', 'مصرف آلومینیوم با موفقیت بروزرسانی شد.');
        return redirect()->route('aluminum_expenses.index', ['type' => $request->type]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $expense = AluminumExpense::findOrFail($id);

        // تشخیص type قبل از حذف
        $type = $expense->transaction_type == 'sale' ? 'sell_expense' : 'purchase_expense';

        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'مصرف آلومینیوم با موفقیت حذف شد.',
            'type' => $type
        ]);
    }




}
