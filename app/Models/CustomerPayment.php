<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'customer_id',
        'payment_date',
        'amount',
        'remarks',
    ];

    // پرداخت مربوط به یک فروش است
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // پرداخت مربوط به یک مشتری است
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
