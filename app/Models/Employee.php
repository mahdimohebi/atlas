<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'father_name', 'address', 'tazkira_no',
        'phone', 'job_position', 'contract_type','is_active'
    ];


    //هر کارمند می‌تواند چند قرارداد داشته باشد.
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }


    //هر کارمند می‌تواند چند معاش دریافت کند.
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }


    //هر کارمند هر روز یک حاضری دارد.

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // ریخت‌ها
    public function pouringPots()
    {
        return $this->hasMany(PouringPot::class);
    }

    // دیزاین‌ها
    public function designPots()
    {
        return $this->hasMany(DesignPot::class);
    }
}
