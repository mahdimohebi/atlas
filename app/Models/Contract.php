<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contract extends Model
{
    use HasFactory;

protected $fillable = [
    'employee_id',
    'duration',
    'start_date',
    'end_date',
    'pricing_type',
    'price_per_item',
    'price_per_kg',
    'guarantee_type',
    'contract_photo',
];

    /**
     * هر قرارداد متعلق به یک کارمند است.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * هر قرارداد می‌تواند چند ضمانت داشته باشد.
     */
    public function guarantee()
    {
        return $this->hasOne(Guarantee::class,'contract_id');
    }
}
