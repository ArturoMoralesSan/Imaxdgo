<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaceRegistration extends Model
{
    use HasFactory;

    protected $appends = ['amount'];

    public function getAmountAttribute() {
        return number_format($this->cost, 2);
    }

    

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
