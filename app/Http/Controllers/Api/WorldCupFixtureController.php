<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorldCupFixtureResource;
use App\Models\WorldCupFixture;
use Illuminate\Http\Request;

class WorldCupFixtureController extends Controller
{
    public function fixtures()
    {
        $fixtures = WorldCupFixture::where('is_finished', false)
            ->where('is_stream_enabled', true)
            ->orderBy('utc_kickoff_at')
            ->get();

        return WorldCupFixtureResource::collection($fixtures);
    }
}
