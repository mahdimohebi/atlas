<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * نمایش لیست حاضری‌ها
     */
    public function index()
    {
        $today = date('Y-m-d');

        $employees = Employee::where('contract_type', 'rozmozd')
            ->with(['attendances' => function($q) use ($today) {
                $q->where('date', $today);
            }])
            ->get();

        $jalaliDate = Jalalian::fromDateTime($today)->format('Y/m/d');

        return view('backend.attendance.index', compact('employees', 'today', 'jalaliDate'));
    }

    /**
     * ذخیره حاضری روزانه
     */
    public function store(Request $request)
    {
        // اعتبارسنجی اولیه
        $request->validate([
            'employee_id' => 'required|array',
            'status' => 'required|array',
            'date' => 'required|date',
        ]);

        foreach ($request->employee_id as $i => $emp_id) {
            $date = $request->date;
            // روز هفته به فارسی
            $dayName = Jalalian::fromDateTime($date)->format('l');

            Attendance::updateOrCreate(
                ['employee_id' => $emp_id, 'date' => $date],
                [
                    'status' => $request->status[$i],
                    'description' => $request->description[$i] ?? null,
                    'day_name' => $dayName,
                ]
            );
        }

        return back()->with('success', 'حاضری روزانه برای همه کارمندان ثبت شد.');
    }

    /**
     * نمایش یک حاضری مشخص
     */
    public function show($employee)
    {
        // 1️⃣ کارمند روزمزد با ID مشخص
        $employee = Employee::where('contract_type', 'rozmozd')
            ->where('id', $employee)
            ->firstOrFail();

        // 2️⃣ همه attendances کارمند
        $attendances = $employee->attendances()
            ->orderBy('date', 'desc')
            ->get();

        // 3️⃣ تعداد حاضر و غایب ماه جاری (Query Builder سریع)
        $presentCount = $employee->attendances()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'present')
            ->count();

        $absentCount = $employee->attendances()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'absent')
            ->count();

        // 4️⃣ ارسال به view
        return view('backend.attendance.show', compact(
            'employee',
            'attendances',
            'presentCount',
            'absentCount'
        ));
    }




    /**
     * فرم ویرایش حاضری
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * بروزرسانی حاضری
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * حذف حاضری
     */
    public function destroy(string $id)
    {
        //
    }

    /**
 * گزارش حاضری کارمندان روزمزد (روزانه، هفتگی، ماهانه)
 */
    public function report(Request $request)
    {
        $filter = $request->input('filter', 'daily'); // daily, weekly, monthly
        $date = $request->input('date', now()->format('Y-m-d'));

        $employees = Employee::where('contract_type', 'rozmozd')
            ->with(['attendances' => function($q) use ($filter, $date) {
                if ($filter === 'daily') {
                    $q->where('date', $date);
                } elseif ($filter === 'weekly') {
                    $startOfWeek = Carbon::parse($date)->startOfWeek();
                    $endOfWeek = Carbon::parse($date)->endOfWeek();
                    $q->whereBetween('date', [$startOfWeek, $endOfWeek]);
                } elseif ($filter === 'monthly') {
                    $startOfMonth = Carbon::parse($date)->startOfMonth();
                    $endOfMonth = Carbon::parse($date)->endOfMonth();
                    $q->whereBetween('date', [$startOfMonth, $endOfMonth]);
                }
            }])
            ->get();

        return view('backend.attendance.report', compact('employees', 'filter', 'date'));

    }


}
