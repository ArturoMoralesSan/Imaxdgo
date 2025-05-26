<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    
    protected $appends = ['count_services'];


    public function getCountServicesAttribute() {
        return $this->services->count();
    }

    /**
     * Get the links that belong to the submenu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
