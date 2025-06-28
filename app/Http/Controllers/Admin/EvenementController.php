<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvenementController extends Controller
{
    public function index() {
        $evenements = Event::with('organisateur')->latest()->get();
        return view('admin.evenements.index', compact('evenements'));
    }

    public function valider($id)
{
    $evenement = Event::findOrFail($id);
    $evenement->estValide = true;
    $evenement->save();

    return redirect()->back()->with('success', 'Événement validé avec succès.');
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
            'typeplacement' => 'nullable|string|max:255',
            //'capacite' => 'required|integer|min:1',
            'idorganisateur' => 'required|exists:organisateurs,idOrganisateur', 
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


    public function refuser($id)
{
    $evenement = Event::findOrFail($id);
    $evenement->estValide = false;
    $evenement->save();

    return redirect()->back()->with('success', 'Événement refusé avec succès.');
}


    // Nouvelle méthode pour basculer le statut (pour le toggle switch)
   public function toggleValidation($id)
    {
        try {
            $evenement = Event::findOrFail($id);
            $evenement->estValide = !$evenement->estValide;
            $evenement->save();

            return response()->json([
                'success' => true,
                'message' => $evenement->estValide
                    ? 'Evenement validé avec succès' 
                    : 'Evenement refuser avec succès',
                'estActif' => $organisateur->estActif
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut'
            ], 500);
        }
    }

    public function rules()
{
    return [
        'nom' => 'required',        // <-- Ces lignes sont le problème
        'prenom' => 'required',     // <-- Ces lignes sont le problème
        'dateDebut' => 'required|date',
        'dateFin' => 'required|date|after_or_equal:dateDebut',
        // ... autres règles pour l'événement
    ];
}
}
