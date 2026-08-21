<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'giveaway_id',
        'name',
        'instagram',
        'folio',
        'status',
        'prize_type',
        'prize_delivered',
    ];

    protected $casts = [
        'prize_delivered' => 'boolean',
    ];

    public function giveaway()
    {
        return $this->belongsTo(Giveaway::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isValidated()
    {
        return $this->status === 'validated';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }
}
