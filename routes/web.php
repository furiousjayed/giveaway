<?php

use App\Http\Controllers\GiveawayParticipantController;
use App\Models\WorldCupFixture;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::view('/', 'home.index');

Route::get('/giveaway/{giveaway}', [GiveawayParticipantController::class, 'show'])->name('giveaway.participate');
Route::post('/giveaway/{giveaway}', [GiveawayParticipantController::class, 'store'])->name('giveaway.participate.store');
Route::get('terms-of-service', [GiveawayParticipantController::class, 'terms']);
Route::get('privacy-policy', [GiveawayParticipantController::class, 'privacy']);
