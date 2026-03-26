<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DesignPot extends Model
{
    use HasFactory;

    protected $table = 'design_pots'; // نام جدول

    protected $fillable = [
        'employee_id',
        'pot_type',       // نوع دیگ
        'sub_type',       // نوع فرعی
        'date',
        'color',          // رنگ یا پالیش
        'weight',
        'price_per_kg',
        'quantity',
        'total_price',
        'note',
    ];

    // رابطه با کارمند
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
