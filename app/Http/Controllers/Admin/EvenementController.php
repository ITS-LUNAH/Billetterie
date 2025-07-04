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
        $organisateurs = Organisateur::all();
        return view('admin.evenements.edit', compact('evenement', 'organisateurs'));
    }

    public function update(Request $request, $id)
    {
        \Log::info('Form data received:', $request->all());
        
        $evenement = Event::findOrFail($id);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            //'dateEvenement' => 'required|date',
            'lieu' => 'required|string|max:255',
            //'capacite' => 'required|integer|min:1',
            'typeplacement' => 'required|in:libre,numerote,debout,mixte',
        ]);

        // Gérer idorganisateur séparément
        $idorganisateur = $request->input('idorganisateur');
        
        // Si idorganisateur est vide, essayer de garder l'ancienne valeur
        if (empty($idorganisateur)) {
            $idorganisateur = $evenement->idorganisateur;
        }
        
        // Vérifier que l'organisateur existe
        if ($idorganisateur && !Organisateur::where('idorganisateur', $idorganisateur)->exists()) {
            return back()->withErrors(['idorganisateur' => 'L\'organisateur sélectionné n\'existe pas.'])
                        ->withInput();
        }

        // Mettre à jour l'événement
        $evenement->titre = $request->titre;
        $evenement->description = $request->description;
        //$evenement->dateEvenement = $request->dateEvenement;
        $evenement->lieu = $request->lieu;
        //$evenement->capacite = $request->capacite;
        $evenement->typeplacement = $request->typeplacement;
        $evenement->estValide = $request->has('estValide') ? true : false;
        
        if (!empty($idorganisateur)) {
            $evenement->idorganisateur = $idorganisateur;
        }
        
        $evenement->save();

        return redirect()->route('admin.evenements.index')
                        ->with('success', 'Événement mis à jour avec succès.');
    }

    // Méthode pour supprimer un événement
    public function destroy($id) {
        $evenement = Event::findOrFail($id);
        $evenement->delete();
        return back()->with('success', 'Événement supprimé.');
    }

    public function refuser($id)
    {
        $evenement = Event::findOrFail($id);
        $evenement->estValide = false;
        $evenement->save();

        return redirect()->back()->with('success', 'Événement refusé avec succès.');
    }

    public function toggleValidation($id)
    {
        try {
            $evenement = Event::findOrFail($id);
            $evenement->estValide = !$evenement->estValide;
            $evenement->save();

            return response()->json([
                'success' => true,
                'message' => $evenement->estValide
                    ? 'Événement validé avec succès' 
                    : 'Événement refusé avec succès',
                'estValide' => $evenement->estValide
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut'
            ], 500);
        }
    }
}