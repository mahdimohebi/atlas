<?php

// app/Models/PotNumber.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotNumber extends Model
{
    protected $fillable = ['pottype_id', 'pot_number'];

    public function potType()
    {
        return $this->belongsTo(PotType::class, 'pottype_id');
    }

    public function potSubtypes()
    {
        return $this->hasMany(PotSubtype::class, 'potnumber_id');
    }
}

