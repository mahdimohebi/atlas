<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Saleitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'pot_type',
        'pot_number',
        'pot_design',
        'quantity',
        'unit_price',
        'total_price',
        'remarks',
    ];

    // هر قلم جنس مربوط به یک فروش است
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
