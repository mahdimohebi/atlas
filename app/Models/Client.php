<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name','f_name', 'phone', 'address'];

    
     //یک مشتری می‌تواند چندین معامله (Transaction) داشته باشد.
     
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
