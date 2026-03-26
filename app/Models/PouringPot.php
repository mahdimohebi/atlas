<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PouringPot extends Model
{
    use HasFactory;

    protected $table = 'pouring_pots'; // نام جدول

    protected $fillable = [
        'employee_id',
        'pot_type',       // نوع دیگ
        'pot_sub_type',   // نوع فرعی دیگ
        'pot_number',     // شماره دیگ
        'date',
        'weight',
        'price_per_kg',
        'total_weight',
        'total_price',
        'note',
    ];

    // رابطه با کارمند
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
