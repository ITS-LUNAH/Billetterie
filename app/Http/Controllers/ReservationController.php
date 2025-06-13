<?php

namespace App\Http\Controllers;

use App\Models\Event; 
use App\Models\Category;
use App\Models\Reservation;
use App\Models\Billet;
use App\Models\ZoneTarifaire;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\Participant;
use App\Models\DateEvenement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    //
    /**
     * Affiche la page de sélection de catégorie pour un événement
     *
     * @param \App\Models\Event $evenement
     * @return \Inertia\Response
     */
    
    public function createCategorySelection(Event $evenement)
    {
        // Débogage pour voir l'ID reçu
    \Log::info('ID de l\'événement reçu dans createCategorySelection:', [
        'idevenement' => $evenement->idevenement,
        'type_placement' => $evenement->typeplacement
    ]);
    
        if ($evenement->typeplacement == 'Libre') {
        
        // Récupérer la prochaine date d'événement
        $prochainDate = $evenement->dates()
            ->where('datedebut', '>=', now())
            ->orderBy('datedebut')
            ->first();
            
        if (!$prochainDate) {
            return redirect()->back()->with('error', 'Aucune date future disponible pour cet événement.');
        }
        // Récupérer la prochaine date d'événement
        $prochainDate = $evenement->dates()
            ->where('datedebut', '>=', now())
            ->orderBy('datedebut')
            ->first();
            
        if (!$prochainDate) {
            return redirect()->back()->with('error', 'Aucune date future disponible pour cet événement.');
        }
        // Récupérer les zones tarifaires pour le lieu de l'événement
        $zones = ZoneTarifaire::where('idlieu', $prochainDate->idlieu)->get();
        
        // Si aucune zone n'est définie, on utilisaera des zones par défaut
        if ($zones->isEmpty()) {
            $zones = [
                [
                    'idzone' => 1,
                    'nomzone' => 'Standard',
                    'tarif' => $evenement->prixbase,
                    'couleur' => '#90EE90',
                    'capacite' => 100,
                    'places_disponibles' => 100
                ],
                [
                    'idzone' => 2,
                    'nomzone' => 'VIP',
                    'tarif' => $evenement->prixbase * 1.5,
                    'couleur' => '#ADD8E6',
                    'capacite' => 50,
                    'places_disponibles' => 50
                ],
                [
                    'idzone' => 3,
                    'nomzone' => 'Premium',
                    'tarif' => $evenement->prixbase * 2,
                    'couleur' => '#FFD700',
                    'capacite' => 20,
                    'places_disponibles' => 20
                ]
            ];

        } else {
            // Calculer les places disponibles pour chaque zone
            $zones = $zones->map(function ($zone) {
                $billetsVendus = Billet::where('idzone', $zone->idzone)->count();
                $zone->places_disponibles = $zone->capacite - $billetsVendus;
                return $zone;
            });
        }
        return Inertia::render('Reservation/CategorySelection', [
            'evenement' => [
                'idevenement' => $evenement->idevenement,
                'titre' => $evenement->titre,
                'image' => $evenement->bandeannonce ? asset('storage/events-photos/' . $evenement->bandeannonce) : null,
                'prix' => $evenement->prixbase,
                'type_placement' => $evenement->typeplacement,
                'date_id' => $prochainDate->iddate
            ],
            'zones' => $zones,
            'canLogin' => route('login'),
            'canRegister' => route('register.participant')
        ]);
        
        }
        elseif ($evenement->typeplacement == 'Numéroté') {
            // Rediriger vers la page de réservation numérotée
        }
    }

    /**
     * Enregistre une nouvelle réservation
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Valider les données reçues
        $request->validate([
            'evenement_id' => 'required|exists:evenements,idevenement',
            'date_id' => 'required|exists:date_evenements,iddate',
            'tickets' => 'required|array',
            'tickets.*.zone_id' => 'required|integer|exists:zone_tarifaire,idzone',
            'tickets.*.quantity' => 'required|integer|min:0',
            'type' => 'required|in:Libre,Numéroté'
        ]);
// Filtrer les tickets avec une quantité > 0
        $ticketsToSave = array_filter($request->tickets, function($ticket) {
            return $ticket['quantity'] > 0;
        });

        if (empty($ticketsToSave)) {
            return back()->withErrors(['tickets' => 'Vous devez sélectionner au moins un ticket.']);
        }

        // Vérifier si l'utilisateur est connecté et récupérer le participant
        $participant = null;
        if (Auth::check()) {
            $participant = Participant::where('user_id', Auth::id())->first();
            
            if (!$participant) {
                // Créer un participant si nécessaire
                $participant = new Participant();
                $participant->user_id = Auth::id();
                $participant->save();
            }
        } else {
            // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            return redirect()->route('login')->with('message', 'Veuillez vous connecter pour réserver des billets.');
        }

        // Commencer une transaction pour garantir l'intégrité des données
        DB::beginTransaction();

        try {
            // Créer la réservation
            $reservation = new Reservation();
            $reservation->idparticipant = $participant->idparticipant;
            $reservation->datereservation = now();
            $reservation->etat = 'en attente';
            $reservation->save();

            // Récupérer l'événement et la date
            $evenement = Event::findOrFail($request->evenement_id);
            $dateEvenement = DateEvenement::findOrFail($request->date_id);

            // Créer les billets pour chaque zone
            foreach ($ticketsToSave as $ticket) {
                $zone = ZoneTarifaire::findOrFail($ticket['zone_id']);
                
                // Vérifier la disponibilité
                $billetsVendus = Billet::where('idzone', $zone->idzone)->count();
                $placesDisponibles = $zone->capacite - $billetsVendus;
                
                if ($placesDisponibles < $ticket['quantity']) {
                    throw new \Exception("Plus assez de places disponibles dans la zone {$zone->nomzone}.");
                }
                
                // Créer les billets individuels
                for ($i = 0; $i < $ticket['quantity']; $i++) {
                    $billet = new Billet();
                    $billet->idevenement = $evenement->idevenement;
                    $billet->idzone = $zone->idzone;
                    $billet->idparticipant = $participant->idParticipant;
                    $billet->idreservation = $reservation->idreservation;
                    $billet->estscanne = false;
                    $billet->QRcode = Billet::generateQRCode();
                    $billet->save();
                }
                
                // Mettre à jour le nombre de places vendues pour la date
                $dateEvenement->placesvendues += $ticket['quantity'];
                $dateEvenement->save();
            }

            DB::commit();

            // Rediriger vers la page de paiement
            return redirect()->route('payment.create', $reservation->idreservation);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}