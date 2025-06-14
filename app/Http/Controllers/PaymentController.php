<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Affiche la page de paiement pour une réservation
     */
    public function create(Reservation $reservation)
    {
        // Vérifier que la réservation appartient à l'utilisateur connecté
        if (auth()->check() && $reservation->participant->user_id != auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette réservation.');
        }
        
        // Charger les billets avec leurs zones
        $reservation->load('billets.zone', 'billets.evenement');
        
        // Regrouper les billets par zone pour l'affichage
        $billetsByZone = $reservation->billets->groupBy('idzone')->map(function ($billets) {
            $zone = $billets->first()->zone;
            return [
                'zone_id' => $zone->idzone,
                'zone_name' => $zone->nomzone,
                'price_per_ticket' => $zone->tarif,
                'quantity' => $billets->count(),
                'total' => $zone->tarif * $billets->count()
            ];
        })->values();
        
        // Récupérer l'événement du premier billet
        $evenement = $reservation->billets->first()->evenement;
        
        return Inertia::render('Payment/Create', [
            'reservation' => [
                'idreservation' => $reservation->idreservation,
                'datereservation' => $reservation->datereservation->format('d/m/Y H:i'),
                'etat' => $reservation->etat,
                'montant_total' => $reservation->montantTotal
            ],
            'evenement' => [
                'idevenement' => $evenement->idevenement,
                'titre' => $evenement->titre,
                'image' => $evenement->bandeannonce ? asset('storage/events-photos/' . $evenement->bandeannonce) : null
            ],
            'billets_by_zone' => $billetsByZone,
            'canLogin' => route('login'),
            'canRegister' => route('register.participant')
        ]);
    }
    
    /**
     * Traite le paiement (à implémenter plus tard)
     */
    public function store(Request $request, Reservation $reservation)
    {
        // Cette méthode sera implémentée plus tard pour traiter le paiement réel
        
        // Pour l'instant, simulons un paiement réussi
        $reservation->etat = 'payé';
        $reservation->save();
        
        return redirect()->route('reservation.confirmation', $reservation->idreservation);
    }
}
