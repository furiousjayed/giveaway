<?php

namespace App\Http\Controllers;

use App\Models\Giveaway;
use App\Models\Participant;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $giveaways = Giveaway::where('status', 'active')
            ->with('user', 'participants')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalGiveaways = Giveaway::count();
        $totalParticipants = Participant::count();
        $totalPrizes = Giveaway::sum('prize_amount');

        return view('public.home', [
            'giveaways' => $giveaways,
            'totalGiveaways' => $totalGiveaways,
            'totalParticipants' => $totalParticipants,
            'totalPrizes' => $totalPrizes,
        ]);
    }
}
