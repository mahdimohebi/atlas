<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoryPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'f_name',
        'category',
        'purchase_date',
        'quantity',
        'waste',
        'net_weight',
        'price_per_unit',
        'total_price',
        'note',
    ];

    /* =========================
        Relations
    ========================= */

    // هر خرید چند پرداخت دارد
    public function payments()
    {
        return $this->hasMany(FactoryPurchasePayment::class);
    }

    /* =========================
        Helpers (محاسبات)
    ========================= */

    // مجموع پرداختی‌ها
    public function totalPaid()
    {
        return $this->payments()->sum('amount');
    }

    // باقیمانده پول
    public function remaining()
    {
        return $this->total_price - $this->totalPaid();
    }
}
