<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        // Ici, vous récupéreriez les événements depuis la base de données
        // Exemple: $events = \App\Models\Event::all();

        return Inertia::render('Events/Index', [
            // 'events' => $events, // Passez les données d'événements à votre composant Vue
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
            'id' => $event->idevenement,
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
    
}