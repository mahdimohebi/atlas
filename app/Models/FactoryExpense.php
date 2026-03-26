<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactoryExpense extends Model
{
    protected $fillable = [
        'expense_type',
        'date',
        'price',
        'notes',
    ];
}
