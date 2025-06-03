<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Doctor extends Model
{
    use HasFactory;

    
    protected $appends = ['count_services', 'last_service_date', 'added_by_branch_name'];


    public function getCountServicesAttribute() {
        return $this->services->count();
    }

    public function getLastServiceDateAttribute() {
        $lastService = $this->services()->orderBy('created_at', 'desc')->first();

        if ($lastService) {
            Carbon::setLocale('es');
            return Carbon::parse($lastService->created_at)->diffForHumans();
        }

        return 'No ha enviado';
    }

    public function getAddedByBranchNameAttribute()
    {
        return $this->branch_name ?? 'No asignado';
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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
