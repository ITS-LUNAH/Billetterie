<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
   public function index()
{
    $participants = Participant::with(['user' => function($query) {
            $query->select('id', 'firstName', 'lastName', 'email', 'telephone');
        }])
        ->withCount(['reservations', 'billets'])
        ->get();

    return view('admin.participants.index', compact('participants'));
}

public function edit($id)
    {
        $participant = Participant::with('user')->findOrFail($id);

        return view('admin.participants.edit', compact('participant'));
    }

    public function update(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);

        $participantData = $request->validate([
            'compteBancaire' => 'nullable|string|max:255',
            'IBAN' => 'nullable|string|max:255',
            'RIB' => 'nullable|string|max:255',
        ]);

        $participant->update($participantData);

        if ($participant->user) {
            $userData = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $participant->user->id,
                'telephone' => 'nullable|string|max:20',
            ]);
            $participant->user->update($userData);
        }

        return redirect()->route('admin.participants.index')->with('success', 'Participant mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);

        if ($participant->user) {
            $participant->user->delete(); 
        }

        $participant->delete(); 

        return redirect()->route('admin.participants.index')->with('success', 'Participant supprimé avec succès.');
    }
}
