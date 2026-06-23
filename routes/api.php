<?php

use App\Http\Controllers\Api\WorldCupFixtureController;
use App\Http\Middleware\DiscordBotApiAuth;
use Illuminate\Support\Facades\Route;

Route::get('world-cup/fixtures', [WorldCupFixtureController::class, 'fixtures'])
    ->middleware([DiscordBotApiAuth::class]);
