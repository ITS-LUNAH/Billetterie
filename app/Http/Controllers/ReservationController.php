<?php

namespace App\Http\Controllers;

use App\Models\Event; 
use App\Models\Category; // Si vous avez un modèle pour les catégories de billets
use App\Models\Reservation;
use App\Models\Billet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

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
        if ($evenement->type_placement == 'Libre') {
        

        // Récupérer toutes les zones tarifaires disponibles
        // Si vos zones sont liées à un lieu et vos événements ont un idLieu, vous pouvez filtrer:
        $zones = ZoneTarifaire::where('idlieu', $evenement->idlieu)->get();
        // Sinon, si les zones sont génériques:
        //$zones = ZoneTarifaire::all();

        return Inertia::render('Reservation/CategorySelection', [
            'evenement' => [
                'id' => $evenement->idevenement, 
                'titre' => $evenement->titre,
                // Ajoutez d'autres infos de l'événement si nécessaire pour la page de réservation
            ],
            'zones' => $zones->map(function ($zone) {
                return [
                    'idZone' => $zone->idzone,
                    'nomZone' => $zone->nomzone,
                    'tarif' => $zone->tarif,
                    'capacite' => $zone->capacite,
                    'couleur' => $zone->couleur,
                ];
            }), // Passe les catégories à la vue
        ]);
        }
        elseif ($evenement->type_placement == 'Numéroté') {
            // Rediriger vers la page de réservation numérotée
            return redirect()->route('evenement.show', $evenement)->with('error', 'Cet événement n\'est pas en placement libre.');
        }
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
            'type' => ['required', 'in:Libre,Numéroté'],
            'tickets' => ['array', 'required_if:type,Libre'], // Pour placement libre
            'tickets.*.zone_id' => ['required_if:type,Libre', 'integer'],
            'tickets.*.quantity' => ['required_if:type,Libre', 'integer', 'min:0'], // La quantité peut être 0 au début

            'selected_seats' => ['array', 'required_if:type,Numéroté'], // Pour placement numéroté
            'selected_seats.*' => ['integer'],
            'total_price' => ['required_if:type,Numéroté', 'numeric', 'min:0'],
        ]);

        $evenement = Event::find($request->evenement_id);


        if (!$evenement) {
            return back()->with('error', 'Événement introuvable.');
        }

        if ($request->type === 'Libre') {
            $totalTickets = 0;
            foreach ($request->tickets as $ticketData) {
                $totalTickets += $ticketData['quantity'];
            }

            if ($totalTickets === 0) {
                return back()->with('error', 'Veuillez sélectionner au moins un ticket.');
            }
            
            // Créer une seule réservation pour l'ensemble des tickets achetés en placement libre
            // Vous pouvez choisir l'état initial (par exemple 'pending', 'paid', etc.)
             $reservation = Reservation::create([
                'datereservation' => now(),
                'etat' => 'pending', // Ou 'confirmed' si le paiement est géré ailleurs
                'idParitcipant' => 1 , // L'utilisateur connecté
            ]);

                foreach ($request->tickets as $ticketData) {
                $zone = ZoneTarifaire::find($ticketData['zone_id']);

                if ($zone && $ticketData['quantity'] > 0) {
                    // Vérifier la capacité restante de la zone (simple, sans gestion concurrence)
                    // Il faudrait une logique plus robuste pour la capacité réelle (billets vendus vs capacité zone)
                    // For now, let's assume capacity management is handled at a higher level or for physical seats only.

                    for ($i = 0; $i < $ticketData['quantity']; $i++) {
                        Billet::create([
                            'idevenement' => $evenement->idevenement,
                            'idreservation' => $reservation->idreservation,
                            'idzone' => $zone->idZone, // Lier le billet à la zone tarifaire
                            'estscanne' => false,
                            'QRcode' => 'QR-'.Str::random(10).'-'.$evenement->idevenement.'-'.$zone->idZone, // Code QR unique
                            // Si vous avez un modèle Participant, vous devrez le gérer ici
                            // 'idParticipant' => null, // À définir après si l'utilisateur est le participant
                        ]);
                    }
                }
            }

            return redirect()->route('dashboard')->with('success', 'Réservation de place libre effectuée avec succès ! Vous pouvez retrouver vos billets dans votre espace personnel.');

        } elseif ($request->type === 'Numéroté') {
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

