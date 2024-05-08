<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;


    public function registers()
    {
        return $this->hasMany(RaceRegistration::class);
    }
}
