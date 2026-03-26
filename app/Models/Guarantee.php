<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantee extends Model
{
    use HasFactory;

protected $fillable = [
    'contract_id',
    'guarantee_type',
    'amount',
    'name',
    'father_name',
    'tazkira_no',
    'address',
    'phone',
    'photo',
];


    // هر ضمانت مربوط به یک قرارداد است.

    public function contract()
    {
        return $this->belongsTo(Contract::class,'contract_id');
    }
}
