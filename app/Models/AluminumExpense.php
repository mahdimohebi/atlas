<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AluminumExpense extends Model
{
    protected $fillable = [
        'expense_type',
        'date',
        'price',
        'notes',
    ];
}
