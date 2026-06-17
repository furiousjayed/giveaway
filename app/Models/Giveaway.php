<?php

namespace App\Models;

use App\Enums\GiveawayStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Giveaway extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'prize_amount',
        'prize_description',
        'max_participants',
        'status',
        'is_public',
        'starts_at',
        'ends_at',
        'winner_count',
    ];

    protected $casts = [
        'status' => GiveawayStatus::class,
        'is_public' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'prize_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function getRemainingSlots(): int
    {
        return $this->max_participants - $this->participants()->count();
    }

    public function getParticipantCount(): int
    {
        return $this->participants()->count();
    }

    public function isFull(): bool
    {
        return $this->getParticipantCount() >= $this->max_participants;
    }

    public function isActive(): bool
    {
        return $this->status === GiveawayStatus::ACTIVE;
    }
}

