<?php

namespace App\Http\Controllers;

use App\Models\Event; 
use App\Models\Category; // Si vous avez un modèle pour les catégories de billets
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    //
    /**
     * Affiche la page de sélection de catégorie pour un événement en placement libre.
     *
     * @param \App\Models\Event $evenement
     * @return \Inertia\Response
     */
    public function createCategorySelection(Event $evenement)
    {
        $zones = ZoneTarifaire::all();

        return Inertia::render('Reservation/CategorySelection', [
            'evenement' => [
                'id' => $evenement->idevenement, 
                'titre' => $evenement->titre,
                // Ajoutez d'autres infos de l'événement si nécessaire pour la page de réservation
            ],
            'zones' => $zones, // Passe les catégories à la vue
        ]);
    }

    /**
     * Gère la soumission d'une réservation (qu'elle soit libre ou numérotée).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valider les données reçues
        $request->validate([
            'evenement_id' => ['required', 'exists:evenements,idevenement'], // Assurez-vous que 'evenements' est le nom de votre table
            'type' => ['required', 'in:Libre,numéroté'],
            'tickets' => ['array', 'required_if:type,Libre'], // Pour placement libre
            'tickets.*.zone_id' => ['required_if:type,Libre', 'integer'],
            'tickets.*.quantity' => ['required_if:type,Libre', 'integer', 'min:0'], // La quantité peut être 0 au début

            'selected_seats' => ['array', 'required_if:type,numéroté'], // Pour placement numéroté
            'selected_seats.*' => ['integer'],
            'total_price' => ['required_if:type,numéroté', 'numeric', 'min:0'],
        ]);

        $evenement = Event::find($request->evenement_id);

        if ($request->type === 'Libre') {
            // Logique de réservation pour le placement libre
            // Parcourir les tickets et les quantités
            foreach ($request->tickets as $ticketData) {
                if ($ticketData['quantity'] > 0) {
                    // Récupérer la zone pour le tarif réel
                    $zone = ZoneTarifaire::find($ticketData['zone_id']);
                    if ($zone) {
                        // Ici, vous enregistreriez la réservation et les billets
                        // Utiliser $zone->idZone, $zone->tarif, $ticketData['quantity']
                        // Pour les billets individuels, vous devrez créer $ticketData['quantity'] billets.
                        // Créer une réservation si ce n'est pas déjà fait
                        $reservation = \App\Models\Reservation::firstOrCreate(
                            ['idParticipant' => auth()->id(), 'etat' => 'en attente'], // Ou un autre statut initial
                            ['datereservation' => now()]
                        );

                        for ($i = 0; $i < $ticketData['quantity']; $i++) {
                            \App\Models\Billet::create([
                                'idEvenement' => $evenement->idevenement,
                                'idZone' => $zone->idzone, // Lier à la zone tarifaire
                                'idReservation' => $reservation->idreservation,
                                'idParticipant' => auth()->id(), // Si l'utilisateur est le participant
                                'codeQR' => 'QR-LIBRE-'.$evenement->idevenement.'-'.$zone->idzone.'-'.Str::random(8),
                                'estScanne' => false,
                                'dateValidation' => null,
                            ]);
                        }
                    }
                }
            }
            // à changer pour rediriger vers la page de payement
            return redirect()->route('dashboard')->with('success', 'Réservation de place libre effectuée avec succès !');

        } elseif ($request->type === 'numéroté') {
            // Logique de réservation pour le placement numéroté
            // Ici, vous enregistreriez les sièges spécifiques sélectionnés en base de données
            // et marqueriez ces sièges comme réservés.
            // Exemple très simplifié :
            // foreach ($request->selected_seats as $seatNumber) {
            //     Seat::where('evenement_id', $evenement->idevenement)->where('seat_number', $seatNumber)->update(['is_booked' => true]);
            // }
            // Reservation::create([
            //     'evenement_id' => $evenement->idevenement,
            //     'selected_seats' => json_encode($request->selected_seats), // Stocker les sièges comme JSON
            //     'total_price' => $request->total_price,
            //     'user_id' => auth()->id(),
            //     'type' => 'numerote',
            // ]);
            echo "Réservation numérotée: " . implode(', ', $request->selected_seats) . " pour {$evenement->titre}<br>";

            // Rediriger vers une page de succès
            return redirect()->route('dashboard')->with('success', 'Réservation de sièges numérotés effectuée avec succès !');
        }

        return back()->with('error', 'Type de placement non valide.');
    }
}

