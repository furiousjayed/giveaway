<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Winner extends Model
{
    protected $fillable = [
        'giveaway_id',
        'participant_id',
        'rank',
        'selected_at',
        'notified_at',
        'claimed_at',
    ];

    protected $casts = [
        'selected_at' => 'datetime',
        'notified_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    public function giveaway(): BelongsTo
    {
        return $this->belongsTo(Giveaway::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function isClaimed(): bool
    {
        return $this->claimed_at !== null;
    }

    public function isNotified(): bool
    {
        return $this->notified_at !== null;
    }
}

