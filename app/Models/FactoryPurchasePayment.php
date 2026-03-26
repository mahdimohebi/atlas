<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoryPurchasePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'factory_purchase_id',
        'payment_date',
        'amount',
        'note',
    ];

    /* =========================
        Relations
    ========================= */

    public function purchase()
    {
        return $this->belongsTo(FactoryPurchase::class, 'factory_purchase_id');
    }
}
