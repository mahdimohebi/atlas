<?php

// app/Models/PotSubtype.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotSubtype extends Model
{
    protected $fillable = ['potnumber_id', 'name'];

    public function potNumber()
    {
        return $this->belongsTo(PotNumber::class, 'potnumber_id');
    }
}
