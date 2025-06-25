<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganisateurController extends Controller
{
    public function index() {
        // Eager load the 'user' relationship and select specific columns
        $organisateurs = Organisateur::with(['user' => function($query) {
            $query->select('id', 'email'); // Select 'id' and 'email' from the users table
        }])->get();

        return view('admin.organisateurs.index', compact('organisateurs'));
    }

    public function show($id)
    {
        $organisateur = Organisateur::findOrFail($id);
        return view('admin.organisateurs.show', compact('organisateur')); // Assuming you might create a show.blade.php
    }

    public function valider($id) {
        $org = Organisateur::findOrFail($id);
        $org->estValide = true;
        $org->save();
        return back()->with('success', 'Organisateur validé.'); // Updated message for consistency
    }

    public function refuser($id) { // New method for refusing validation
        $org = Organisateur::findOrFail($id);
        $org->estValide = false; // Set to false to mark as refused or pending
        $org->save();
        return back()->with('error', 'Organisateur refusé.'); // Using 'error' for refusal
    }

   public function suspendre($id) {
        $org = Organisateur::findOrFail($id);
        $org->estActif = false;
        $org->save();
        return response()->json(['success' => true, 'message' => 'Organisateur suspendu.', 'estActif' => $org->estActif]);
    }

    public function reactiver($id)
    {
        $org = Organisateur::findOrFail($id);
        $org->estActif = true;
        $org->save();
        return response()->json(['success' => true, 'message' => 'Organisateur réactivé.', 'estActif' => $org->estActif]);
    }

    public function edit($id)
    {
        $organisateur = Organisateur::findOrFail($id);
        return view('admin.organisateurs.edit', compact('organisateur'));
    }

    public function update(Request $request, $id)
    {
        $organisateur = Organisateur::findOrFail($id);

        $request->validate([
            'societe' => 'required|string|max:255',
            'nomGerant' => 'required|string|max:255',
            'prenomGerant' => 'required|string|max:255',
            'ICE' => 'nullable|string|max:255',
            'secteurActivite' => 'nullable|string|max:255',
            'estActif' => 'boolean',
            'estValide' => 'boolean',
        ]);

        $organisateur->update($request->all());
        return redirect()->route('admin.organisateurs.index')->with('success', 'Organisateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $organisateur = Organisateur::findOrFail($id);
        $organisateur->delete();
        return back()->with('success', 'Organisateur supprimé avec succès.');
    }

    public function toggleActivation($id)
    {
        try {
            $organisateur = Organisateur::findOrFail($id);
            $organisateur->estActif = !$organisateur->estActif;
            $organisateur->save();

            return response()->json([
                'success' => true,
                'message' => $organisateur->estActif 
                    ? 'Organisateur activé avec succès' 
                    : 'Organisateur désactivé avec succès',
                'estActif' => $organisateur->estActif
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut'
            ], 500);
        }
    }

    public function getNomCompletAttribute()
    {
        return $this->lastName . ' ' . $this->firstName;
    }

    // If you have a relationship to a User model for user_id
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}