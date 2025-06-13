<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EvenementController extends Controller
{   
    public function index()
    {
        $idOrganisateur = auth()->user()->organisateur->idOrganisateur ?? null;
        
        $evenements = \App\Models\Evenement::where('idOrganisateur', $idOrganisateur)->latest()->get();
        
        return view('organisateur.evenements.index', compact('evenements'));
    }

    public function create()
    {
        return view('organisateur.evenements.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'lieu' => 'required|string|max:255',
                'bandeAnnonce' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'typePlacement' => 'required|in:libre,numéroté',
                'prixBase' => 'nullable|numeric',
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $user = auth()->user();
        
        if (!$user->organisateur) {
            return back()->with('error', 'Aucun compte organisateur lié à cet utilisateur.');
        }
        
        $idOrganisateur = $user->organisateur->idOrganisateur;

        // Gestion de l'upload de l'image
        $bandeAnnoncePath = null;
        if ($request->hasFile('bandeAnnonce')) {
            try {
                $bandeAnnoncePath = $request->file('bandeAnnonce')->store('events-photos', 'public');
            } catch (\Exception $e) {
                Log::error('Erreur upload image: ' . $e->getMessage());
            }
        }

        $eventData = [
            'titre' => $validated['titre'],
            'description' => $validated['description'] ?? null,
            'lieu' => $validated['lieu'],
            'bandeannonce' => $bandeAnnoncePath,
            'typeplacement' => $validated['typePlacement'],
            'prixbase' => $validated['prixBase'] ?? 0,
            'idOrganisateur' => $idOrganisateur,
            'estvalide' => false,
        ];

        try {
            $evenement = Evenement::create($eventData);
            return redirect()->route('organisateur.evenements.index')->with('success', 'Événement créé avec succès.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de l\'événement: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $evenement = Evenement::where('idevenement', $id)
                              ->where('idOrganisateur', auth()->user()->organisateur->idOrganisateur)
                              ->firstOrFail();
        
        return view('organisateur.evenements.edit', compact('evenement'));
    }

    public function update(Request $request, $id)
    {
        $evenement = Evenement::where('idevenement', $id)
                              ->where('idOrganisateur', auth()->user()->organisateur->idOrganisateur)
                              ->firstOrFail();

        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'lieu' => 'required|string|max:255',
                'bandeAnnonce' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'typePlacement' => 'required|in:libre,numéroté',
                'prixBase' => 'nullable|numeric',
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        // Gestion de l'upload de la nouvelle image
        $bandeAnnoncePath = $evenement->bandeannonce; // Garder l'ancienne image par défaut
        
        if ($request->hasFile('bandeAnnonce')) {
            try {
                // Supprimer l'ancienne image si elle existe
                if ($evenement->bandeannonce && Storage::disk('public')->exists($evenement->bandeannonce)) {
                    Storage::disk('public')->delete($evenement->bandeannonce);
                }
                
                // Uploader la nouvelle image
                $bandeAnnoncePath = $request->file('bandeAnnonce')->store('events-photos', 'public');
            } catch (\Exception $e) {
                Log::error('Erreur upload image: ' . $e->getMessage());
            }
        }

        try {
            $evenement->update([
                'titre' => $validated['titre'],
                'description' => $validated['description'] ?? null,
                'lieu' => $validated['lieu'],
                'bandeannonce' => $bandeAnnoncePath,
                'typeplacement' => $validated['typePlacement'],
                'prixbase' => $validated['prixBase'] ?? 0,
            ]);
            
            return redirect()->route('organisateur.evenements.index')->with('success', 'Événement modifié avec succès.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $evenement = Evenement::where('idevenement', $id)
                              ->where('idOrganisateur', auth()->user()->organisateur->idOrganisateur)
                              ->firstOrFail();

        try {
            // Supprimer l'image si elle existe
            if ($evenement->bandeannonce && Storage::disk('public')->exists($evenement->bandeannonce)) {
                Storage::disk('public')->delete($evenement->bandeannonce);
            }
            
            $evenement->delete();
            
            return redirect()->route('organisateur.evenements.index')->with('success', 'Événement supprimé avec succès.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}