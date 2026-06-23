<?php

namespace App\Http\Controllers;

use App\Models\Giveaway;
use App\Models\Participant;
use Illuminate\Http\Request;

class GiveawayParticipantController extends Controller
{
    public function show(Giveaway $giveaway)
    {
        $giveaway->load('user', 'participants');

        return view('giveaway.show', [
            'giveaway' => $giveaway,
        ]);
    }

    public function store(Request $request, Giveaway $giveaway)
    {
        if (!$giveaway->isActive()) {
            return back()->withErrors(['error' => 'This giveaway is not active']);
        }

        if ($giveaway->isFull()) {
            return back()->withErrors(['error' => 'This giveaway has reached maximum participants']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:participants,name,NULL,id,giveaway_id,' . $giveaway->id,
            'email' => 'required|email|max:255|unique:participants,email,NULL,id,giveaway_id,' . $giveaway->id,
            'phone' => 'nullable|string|max:20',
        ], [
            'name.unique' => 'This name is already taken for this giveaway',
            'email.unique' => 'This email is already registered for this giveaway',
        ]);

        $validated['giveaway_id'] = $giveaway->id;
        $validated['user_id'] = auth()->id();

        Participant::create($validated);

        return back()->with('success', 'Successfully registered for the giveaway! Good luck!');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }
}
