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
        // Load participant with its associated user
        $participant = Participant::with('user')->findOrFail($id);

        // Return a view with the participant data for editing
        // You'll need to create this view (e.g., admin.participants.edit.blade.php)
        return view('admin.participants.edit', compact('participant'));
    }

    public function update(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);

        // Validate Participant-specific fields
        $participantData = $request->validate([
            'compteBancaire' => 'nullable|string|max:255',
            'IBAN' => 'nullable|string|max:255',
            'RIB' => 'nullable|string|max:255',
            // Add other participant specific fields if any
        ]);

        $participant->update($participantData);

        // Validate and update associated User fields if they are submitted
        if ($participant->user) {
            $userData = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $participant->user->id, // Validate unique email on the 'users' table, excluding current user's email
                'telephone' => 'nullable|string|max:20',
                // Add other user specific fields if any, like password (if you allow changing it here)
            ]);
            $participant->user->update($userData);
        }

        return redirect()->route('admin.participants.index')->with('success', 'Participant mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);

        // Delete the associated user first (if exists)
        // This is important to maintain data integrity and avoid orphaned user accounts
        if ($participant->user) {
            $participant->user->delete(); // This will soft delete the user if SoftDeletes is used on User model
        }

        $participant->delete(); // This will soft delete the participant if SoftDeletes is used

        return redirect()->route('admin.participants.index')->with('success', 'Participant supprimé avec succès.');
    }

    // You might also want to add a 'show' method if you need a dedicated page for participant details
    // public function show($id)
    // {
    //     $participant = Participant::with('user', 'reservations', 'billets')->findOrFail($id);
    //     return view('admin.participants.show', compact('participant'));
    // }
}
