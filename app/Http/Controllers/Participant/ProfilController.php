<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Participant;
use App\Models\User;

class ProfilController extends Controller
{
    /**
     * Affiche le profil du participant
     */
    public function index()
    {
        $user = Auth::user();
        $participant = Participant::where('user_id', $user->id)->first();

        return Inertia::render('Participant/Profil', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at->format('d/m/Y'),
            ],
            'participant' => $participant ? [
                'id' => $participant->idParticipant,
                'compte_bancaire' => $participant->compteBancaire,
                'iban' => $participant->IBAN,
                'rib' => $participant->RIB,
            ] : null,
            'message' => 'Votre profil participant'
        ]);
    }

    /**
     * Met à jour le profil
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'compte_bancaire' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:40',
            'rib' => 'nullable|string|max:40',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $participant = Participant::where('user_id', $user->id)->first();
        if ($participant) {
            $participant->update([
                'compteBancaire' => $request->compte_bancaire,
                'IBAN' => $request->iban,
                'RIB' => $request->rib,
            ]);
        }

        return redirect()->route('participant.profil')
            ->with('success', 'Profil mis à jour avec succès');
    }

    /**
     * Affiche la page des paramètres
     */
    public function parametres()
    {
        return Inertia::render('Participant/Parametres', [
            'message' => 'Paramètres de votre compte'
        ]);
    }

    /**
     * Affiche la page de support
     */
    public function support()
    {
        return Inertia::render('Participant/Support', [
            'message' => 'Centre d\'aide et support'
        ]);
    }
}
