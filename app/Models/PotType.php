<?php

// app/Models/PotType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PotType extends Model
{
    protected $fillable = ['name'];

    public function potNumbers()
    {
        return $this->hasMany(PotNumber::class, 'pottype_id');
    }
}

