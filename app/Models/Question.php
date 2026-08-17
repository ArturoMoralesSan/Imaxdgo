<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'giveaway_id',
        'question',
        'type',
        'show_user',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'order',
    ];

    public function giveaway()
    {
        return $this->belongsTo(Giveaway::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function isBoolean()
    {
        return $this->type === 'boolean';
    }

    public function isMultiple()
    {
        return $this->type === 'multiple';
    }
}
