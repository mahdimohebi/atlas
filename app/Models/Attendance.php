<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'date', 'day_name', 'status','description'
    ];

    
    //هر حاضری مربوط به یک کارمند است.
     
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
