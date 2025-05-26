<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Doctor extends Model
{
    use HasFactory;

    
    protected $appends = ['count_services', 'last_service_date'];


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
