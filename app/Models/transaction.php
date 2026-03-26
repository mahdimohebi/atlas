<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'supplier_id',
        'client_id',
        'quantity',
        'price_per_unit',
        'total_price',
        'transaction_date',
        'currency',
        'exchange_rate',
        'description'
    ];

    
    //هر معامله خرید (purchase) می‌تواند متعلق به یک تأمین‌کننده باشد.
     
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    
     //هر معامله فروش (sale) می‌تواند متعلق به یک مشتری باشد.     
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    
     //هر معامله می‌تواند چندین پرداخت داشته باشد.
    public function payments()
    {
        return $this->hasMany(Payment::class,'transaction_id');
    }

    public function totalPaid()
    {
        return $this->payments()->sum('amount');
    }

    public function remaining()
    {
        return $this->total_price - $this->totalPaid();
    }
}
