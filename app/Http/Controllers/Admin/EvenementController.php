<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organisateur;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    public function index() {
        $evenements = Event::with('organisateur')->latest()->get();
        return view('admin.evenements.index', compact('evenements'));
    }

    public function valider($id) {
        $e = Event::findOrFail($id);
        $e->estValide = true;
        $e->save();
        return back()->with('success', 'Événement validé.');
    }


    public function edit($id)
    {
        $evenement = Event::findOrFail($id);
        $organisateurs = Organisateur::all(); // Pour la liste déroulante des organisateurs
        return view('admin.evenements.edit', compact('evenement', 'organisateurs'));
    }

    // Méthode pour mettre à jour un événement
    public function update(Request $request, $id)
    {
        $evenement = Event::findOrFail($id);
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dateEvenement' => 'required|date',
            'lieu' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'organisateur_id' => 'required|exists:organisateurs,id', // Assurez-vous que la colonne ID de l'organisateur est correcte
            // Ajoutez d'autres règles de validation si nécessaire
        ]);

        $evenement->update($request->all());
        return redirect()->route('admin.evenements.index')->with('success', 'Événement mis à jour avec succès.');
    }

    // Méthode pour supprimer un événement
    public function destroy($id) {
        $e = Event::findOrFail($id);
        $e->delete();
        return back()->with('success', 'Événement supprimé.');
    }


    public function refuser($id) {
        $e = Event::findOrFail($id);
        $e->estValide = false;
        $e->save();
        return back()->with('success', 'Événement refusé.');
    }

    // Nouvelle méthode pour basculer le statut (pour le toggle switch)
    public function toggleActivation($id)
    {
        $evenement = Event::findOrFail($id);
        $evenement->estActif = !$evenement->estActif; // Inverse l'état actuel de 'estActif'
        $evenement->save();
        return response()->json([
            'success' => true,
            'estActif' => $evenement->estActif, // Retourne le nouvel état
            'message' => 'Statut d\'activation mis à jour.'
        ]);
    }
}
