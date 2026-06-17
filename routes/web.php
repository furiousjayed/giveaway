<?php

use App\Http\Controllers\GiveawayParticipantController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home.index');

Route::get('/giveaway/{giveaway}', [GiveawayParticipantController::class, 'show'])->name('giveaway.participate');
Route::post('/giveaway/{giveaway}', [GiveawayParticipantController::class, 'store'])->name('giveaway.participate.store');
