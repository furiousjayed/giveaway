<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Participant extends Model
{
    protected $fillable = [
        'giveaway_id',
        'user_id',
        'name',
        'email',
        'phone',
        'entry_date',
    ];

    protected $casts = [
        'entry_date' => 'datetime',
    ];

    public function giveaway(): BelongsTo
    {
        return $this->belongsTo(Giveaway::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function winner(): HasOne
    {
        return $this->hasOne(Winner::class);
    }

    public function isWinner(): bool
    {
        return $this->winner()->exists();
    }
}

