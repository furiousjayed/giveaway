<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorldCupFixtureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'home_team_name' => $this->home_team_name,
            'away_team_name' => $this->away_team_name,

            'utc_kickoff_at' => $this->start_at,
            'unix_time' => $this->start_at->timestamp,
        ];
    }
}
