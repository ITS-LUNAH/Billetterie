<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Evenement;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;


class AccueilController extends Controller
{
    //
    public function index()
    {
        $upcomingEvents = Evenement::where('estvalide', true)
            ->with(['dates' => function($query) {
                $query->where('dateDebut', '>=', Carbon::today())
                    ->orderBy('dateDebut', 'asc');
            }, 'dates.lieu'])
            ->has('dates')
            ->limit(9) // Limite à 9 événements
            ->get()
            ->map(function ($event) {
                $firstUpcomingDate = $event->dates->first();
                return [
                'id' => $event->idevenement,
                'titre' => $event->titre,
                'description' => $event->description,
                'image' => $event->bandeannonce ? asset('storage/events-photos/'. $event->bandeannonce) : null, // Assurez-vous que l'image est accessible via storage:link
                'prix' => $event->prixbase,
                'lieu' => $firstUpcomingDate->lieu->ville . ' - ' . $firstUpcomingDate->lieu->adresse, // Concaténer ville et adresse
                'date' => $firstUpcomingDate->datedebut->translatedFormat('d M Y') . ' à ' . Carbon::parse($firstUpcomingDate->heuredebut)->format('H:i'), // Formater la date et l'heure
                ];
            });

        return Inertia::render('Accueil', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register.participant'),
            'upcomingEvents' => $upcomingEvents, // Passez les événements
        ]);
    }
}
