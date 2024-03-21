<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;


    protected $appends = ['count_services', 'amount_services', 'amount_expenses', 'amount_expenses_raw'];


    public function getCountServicesAttribute() {
        return $this->services->count();
    }
    public function getAmountServicesAttribute() {
        return number_format($this->services->sum('cost'), 2, '.');
    }

    public function getAmountExpensesAttribute() {
        return number_format($this->expenses->sum('amount'), 2, '.');
    }
    public function getAmountExpensesRawAttribute() {
        return $this->expenses->sum('amount');
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
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
