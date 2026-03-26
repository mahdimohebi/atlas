<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    
    protected $fillable = ['name','f_name', 'phone', 'address'];

    
     //یک تأمین‌کننده می‌تواند چندین معامله (Transaction) داشته باشد.
     
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
