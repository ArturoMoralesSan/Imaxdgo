<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Service extends Model
{
    use HasFactory;



    protected $appends = ['formated_date','list_studies','cost'];


    /**
     * Return the slugified name of the section.
     *
     * @return string
     */
    public function getFormatedDateAttribute()
    {
        return Carbon::parse($this->date)->format('d/m/Y');
    }

    

    public function getListStudiesAttribute() {
        return $this->studies->implode('name', ', ');
    }

    public function getCostAttribute() {
        return $this->payments->sum('pivot.cost');
    }

    /**
     * Get the branch that owns the submenu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * A permission can be applied to many studies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function studies()
    {
        return $this->belongsToMany(Study::class);
    }

    /**
     * A permission can be applied to many studies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function payments()
    {
        return $this->belongsToMany(Payment::class)->withPivot('cost');;
    }
}
