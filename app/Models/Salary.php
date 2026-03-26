<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'type', 'amount', 'date', 'notes','status'
    ];


    // هر معاش مربوط به یک کارمند است.

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
