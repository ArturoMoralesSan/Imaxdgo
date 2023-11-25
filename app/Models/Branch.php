<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;


    protected $appends = ['count_services', 'amount_services'];


    public function getCountServicesAttribute() {
        return $this->services->count();
    }
    public function getAmountServicesAttribute() {
        return $this->services->sum('cost');
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

    /**
     * Get the links that belong to the submenu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branch()
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the links that belong to the submenu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
