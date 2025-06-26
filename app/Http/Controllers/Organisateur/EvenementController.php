<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EvenementController extends Controller
{   
    public function index()
    {
        $idOrganisateur = auth()->user()->organisateur->idOrganisateur ?? null;
        
        $evenements = \App\Models\Event::where('idOrganisateur', $idOrganisateur)->latest()->get();
        
        return view('organisateur.evenements.index', compact('evenements'));
    }

    public function create()
    {
        return view('organisateur.evenements.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
        'lieu' => 'required|string|max:255',
        'bandeAnnonce' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'categorie' => 'required|string',
        'prixBase' => 'nullable|numeric|min:0',
    ]);

    // Trouver l’organisateur connecté
    $organisateur = \App\Models\Organisateur::where('user_id', auth()->id())->first();

    if (!$organisateur) {
        return redirect()->back()->with('error', 'Organisateur introuvable.');
    }

    $bandeAnnoncePath = null;

    if ($request->hasFile('bandeAnnonce')) {
        $bandeAnnoncePath = $request->file('bandeAnnonce')->store('bandeannonces', 'public');
    }

    \App\Models\Event::create([
        'titre' => $request->titre,
        'description' => $request->description,
        'lieu' => $request->lieu,
        'bandeannonce' => $bandeAnnoncePath,
        'categorie' => $request->categorie,
        'prixbase' => $request->prixBase ?? 0,
        'estvalide' => false,
        'estActif' => true,
        'idorganisateur' => $organisateur->idOrganisateur,
    ]);

    return redirect()->route('organisateur.evenements.index')->with('success', 'Événement créé avec succès.');
}


    public function edit($id)
    {
        $evenement = Event::where('idevenement', $id)
                              ->where('idOrganisateur', auth()->user()->organisateur->idOrganisateur)
                              ->firstOrFail();
        
        return view('organisateur.evenements.edit', compact('evenement'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
        'lieu' => 'required|string|max:255',
        'bandeAnnonce' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'categorie' => 'required|string',
        'prixBase' => 'nullable|numeric|min:0',
    ]);

    $evenement = \App\Models\Event::findOrFail($id);

    if ($request->hasFile('bandeAnnonce')) {
        $path = $request->file('bandeAnnonce')->store('bandeannonces', 'public');
        $evenement->bandeannonce = $path;
    }

    $evenement->update([
        'titre' => $request->titre,
        'description' => $request->description,
        'lieu' => $request->lieu,
        'categorie' => $request->categorie,
        'prixbase' => $request->prixBase ?? 0,
    ]);

    return redirect()->route('organisateur.evenements.index')->with('success', 'Événement modifié avec succès.');
}


    public function destroy($id)
    {
        $evenement = Event::where('idevenement', $id)
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