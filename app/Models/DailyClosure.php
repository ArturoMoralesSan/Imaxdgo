<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyClosure extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'date',
        'cash_total',
        'card_total',
        'transfer_total',
        'expenses',
        'total',
        'total_delivery',
        'closed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'closed_at' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
