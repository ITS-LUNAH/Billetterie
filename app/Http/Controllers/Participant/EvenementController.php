<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;

class EvenementController extends Controller
{
    /**
     * Affiche la liste des événements disponibles
     */
    public function index(Request $request)
    {
        try {
            // Récupérer les événements validés et publiés
            $query = Event::where('estvalide', true)
                ->orderBy('created_at', 'desc');

            // Filtrer par recherche si fournie
            if ($request->has('search') && $request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('titre', 'like', '%' . $request->search . '%')
                      ->orWhere('lieu', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }

            $evenements = $query->paginate(12)->through(function ($evenement) {
                return [
                    'id' => $evenement->idevenement,
                    'titre' => $evenement->titre,
                    'description' => $evenement->description,
                    'lieu' => $evenement->lieu,
                    'prix_base' => $evenement->prixbase,
                    'type_placement' => $evenement->typeplacement,
                    'bande_annonce' => $evenement->bandeannonce,
                    'date_creation' => $evenement->created_at->format('d/m/Y'),
                ];
            });

            return Inertia::render('Participant/Evenements', [
                'evenements' => $evenements,
                'message' => 'Événements disponibles',
                'filters' => [
                    'search' => $request->search ?? ''
                ]
            ]);

        } catch (\Exception $e) {
            return Inertia::render('Participant/Evenements', [
                'evenements' => [],
                'message' => 'Erreur lors du chargement des événements',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Affiche le détail d'un événement
     */
    public function show($id)
    {
        $evenement = Evenement::where('idevenement', $id)
            ->where('estvalide', true)
            ->first();

        if (!$evenement) {
            return redirect()->route('participant.evenements')
                ->with('error', 'Événement non trouvé');
        }

        return Inertia::render('Participant/EvenementDetail', [
            'evenement' => [
                'id' => $evenement->idevenement,
                'titre' => $evenement->titre,
                'description' => $evenement->description,
                'lieu' => $evenement->lieu,
                'prix_base' => $evenement->prixbase,
                'type_placement' => $evenement->typeplacement,
                'bande_annonce' => $evenement->bandeannonce,
                'date_creation' => $evenement->created_at->format('d/m/Y H:i'),
            ]
        ]);
    }
}
