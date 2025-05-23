<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RaceRegistration extends Model
{
    use HasFactory;

    protected $appends = ['amount', 'formated_date'];

    /**
     * Return the slugified name of the section.
     *
     * @return string
     */
    public function getFormatedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }

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
