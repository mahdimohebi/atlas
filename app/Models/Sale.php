<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'sale_date',
        'remarks',
    ];

    // یک فروش می‌تواند چند قلم داشته باشد
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    // یک فروش متعلق به یک مشتری است
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // پرداخت‌های مرتبط با این فروش
    public function payments()
    {
        return $this->hasMany(CustomerPayment::class);
    }
}

