<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giveaway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'starts_at',
        'ends_at',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class)
            ->orderBy('order');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}