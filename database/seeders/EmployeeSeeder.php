<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Contract;
use App\Models\Guarantee;
use App\Models\Salary;
use App\Models\Attendance;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        // -----------------------------
        // کارمند اول (اجاره‌ای)
        // -----------------------------
        $employee1 = Employee::create([
            'name' => 'احمد',
            'father_name' => 'محمد',
            'address' => 'کابل - کارته نو',
            'tazkira_no' => '123456789',
            'phone' => '0700000000',
            'job_position' => 'کارگر انبار',
            'contract_type' => 'ejaraei'
        ]);

        // قرارداد برای کارمند اول
        $contract1 = Contract::create([
            'employee_id' => $employee1->id,
            'contract_photo' => 'contracts/ahmad.jpg',
            'guarantee_type' => 'naqdi',
            'duration' => 6,
            'weight' => 500,
            'price_per_kg' => 50,
            'total_price' => 25000,
        ]);

        // ضمانت نقدی
        Guarantee::create([
            'contract_id' => $contract1->id,
            'guarantee_type' => 'naqdi',
            'amount' => 10000,
        ]);

        // معاشات برای کارمند اجاره‌ای
        Salary::create([
            'employee_id' => $employee1->id,
            'type' => 'ejaraei',
            'amount' => 5000,
            'date' => Carbon::now()->subDays(5),
            'notes' => 'پرداخت اولیه',
        ]);

        Salary::create([
            'employee_id' => $employee1->id,
            'type' => 'ejaraei',
            'amount' => 3000,
            'date' => Carbon::now(),
            'notes' => 'پرداخت دوم',
        ]);

        // حاضری برای کارمند اول
        for ($i = 0; $i < 5; $i++) {
            Attendance::create([
                'employee_id' => $employee1->id,
                'date' => Carbon::now()->subDays($i),
                'day_name' => Carbon::now()->subDays($i)->format('l'),
                'status' => 'present'
            ]);
        }

        // -----------------------------
        // کارمند دوم (روز‌مزد)
        // -----------------------------
        $employee2 = Employee::create([
            'name' => 'رحیم',
            'father_name' => 'کریم',
            'address' => 'مزار - ناحیه سوم',
            'tazkira_no' => '987654321',
            'phone' => '0799999999',
            'job_position' => 'کارگر ساختمانی',
            'contract_type' => 'rozmozd'
        ]);

        // معاشات روز‌مزد (هر روز فرق می‌کند)
        Salary::create([
            'employee_id' => $employee2->id,
            'type' => 'rozmozd',
            'amount' => 800,
            'date' => Carbon::now()->subDays(1),
            'notes' => 'کار یک روزه',
        ]);

        Salary::create([
            'employee_id' => $employee2->id,
            'type' => 'rozmozd',
            'amount' => 1000,
            'date' => Carbon::now(),
            'notes' => 'کار روز دوم',
        ]);

        // حاضری برای کارمند دوم
        for ($i = 0; $i < 3; $i++) {
            Attendance::create([
                'employee_id' => $employee2->id,
                'date' => Carbon::now()->subDays($i),
                'day_name' => Carbon::now()->subDays($i)->format('l'),
                'status' => ($i % 2 == 0) ? 'present' : 'absent'
            ]);
        }
    }
}
