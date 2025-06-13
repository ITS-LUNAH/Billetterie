<?php

namespace App\Http\Controllers;

use App\Models\DateEvenement;
use App\Models\LieuEvenement;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index(Request $request)
{
    // Ici, vous récupéreriez les événements depuis la base de données
    $events = \App\Models\Event::all();

    
    $search = $request->get('search');
    $location = $request->get('location');
    $date = $request->get('date');

    $events = Event::with(['dates.lieu', 'organisateur'])
        ->search($search, $location, $date)
        ->orderBy('created_at', 'desc')
        ->paginate(12)
        ->withQueryString();

    // Transformer les données pour l'affichage
    $events->getCollection()->transform(function ($event) {
        $prochainDate = $event->prochaine_date;
        
        return [
            'idevenement' => $event->idevenement,
            'titre' => $event->titre,
            'description' => $event->description,
            'lieu' => $event->lieu,
            'bandeannonce' => $event->bandeannonce ? asset('storage/events-photos/' . $event->bandeannonce) : null,
            'prixbase' => $event->prixbase,
            'prochaine_date' => $prochainDate ? [
                'datedebut' => $prochainDate->datedebut,
                'heuredebut' => $prochainDate->heuredebut,
                'lieu_evenement' => $prochainDate->lieu,
                'places_restantes' => $prochainDate->places_restantes
            ] : null
        ];
    });

    return Inertia::render('Evenement/Index', [
        'events' => $events,
        'filters' => [
            'search' => $search,
            'location' => $location,
            'date' => $date,
        ]
    ]);
}

public function search(Request $request)
{
    $request->validate([
        'search' => 'nullable|string|max:255',
        'location' => 'nullable|string|max:255',
        'date' => 'nullable|date',
    ]);

    return redirect()->route('evenement.index', [
        'search' => $request->search,
        'location' => $request->location,
        'date' => $request->date,
    ]);
}

/**
     * Affiche les détails d'un événement spécifique.
     *
     * @param  \App\Models\Event  $event // Laravel va injecter le modèle Event
     * @return \Inertia\Response
     */
    public function show(Event $event) 
    {
        // Charger les relations nécessaires (par exemple, les dates et le lieu)
        $event->load('dates.lieu');

        // Formater les données pour la vue si nécessaire
        $formattedEvent = [
            'idevenement' => $event->idevenement,
            'titre' => $event->titre,
            'description' => $event->description,
            'image' => $event->bandeannonce ? asset('storage/events-photos/' . $event->bandeannonce) : null,
            'prix' => $event->prixbase,
            // Assurez-vous d'avoir au moins une date si vous l'affichez
            'lieu' => $event->dates->first() ? $event->dates->first()->lieu->ville . ' - ' . $event->dates->first()->lieu->adresse : 'Non spécifié',
            'date' => $event->dates->first() ? Carbon::parse($event->dates->first()->datedebut)->translatedFormat('d F Y') : 'Non spécifiée',
            'heuredebut' => $event->dates->first() ? Carbon::parse($event->dates->first()->heuredebut)->format('H:i') : 'Non spécifiée',
            'heureouvertureportes' => $event->dates->first() ? Carbon::parse($event->dates->first()->heureouvertureportes)->format('H:i') : 'Non spécifiée',
            'places_disponibles' => 500, // Exemple statique, à remplacer par votre logique réelle
            'places_reservees' => 187, // Exemple statique, à remplacer par votre logique réelle
            'organisateur_nom' => 'Jazz Festival Productions', // Exemple statique
            'organisateur_verifie' => true, // Exemple statique
            'type_placement' => $event->typeplacement,
        ];

        return Inertia::render('Evenement/Show', [ // Le chemin du composant Vue reste Evenement/Show
            'evenement' => $formattedEvent,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
    
}