<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Override;

class WorldCupFixture extends Model
{
    protected $fillable = [
        'api_match_id',
        'group_code',
        'matchday',
        'match_type',
        'home_team_name',
        'away_team_name',
        'home_scorers',
        'away_scorers',
        'home_score',
        'away_score',
        'stadium_name',
        'city',
        'host_country',
        'timezone',
        'start_at',
        'is_finished',
        'is_stream_enabled',
    ];

    #[Override]
    protected function casts()
    {
        return [
            'matchday' => 'integer',
            'home_score' => 'integer',
            'away_score' => 'integer',
            'home_scorers' => 'json',
            'away_scorers' => 'json',
            'start_at' => 'datetime',
            'is_finished' => 'boolean',
            'is_stream_enabled' => 'boolean',
        ];
    }

    public function title(): string
    {
        return "{$this->home_team_name} vs {$this->away_team_name}";
    }

    public function isStream(): bool
    {
        return $this->is_stream_enabled;
    }

    public function isGroupStage(): bool
    {
        return $this->match_type === 'group';
    }

    public function scopeIsStreamEnabled(Builder $query): Builder
    {
        return $query->where('is_stream_enabled', true);
    }
}
