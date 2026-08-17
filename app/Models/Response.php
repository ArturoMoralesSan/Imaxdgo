<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'question_id',
        'answer',
        'is_correct',
        'verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
