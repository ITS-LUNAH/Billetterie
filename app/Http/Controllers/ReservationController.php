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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;
use App\Mail\TicketsMail;

use Barryvdh\DomPDF\Facade\Pdf;

use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class ReservationController extends Controller
{

    /**
     * Envoie les billets de la réservation par e-mail.
     * @param \App\Models\Reservation $reservation
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTickets(Reservation $reservation)
    {
        // VÉRIFICATIONS DE SÉCURITÉ :
        // 1. S'assure que l'utilisateur connecté est bien le propriétaire de cette réservation.
        //    Ceci est CRUCIAL pour la sécurité.
        if (Auth::check() && Auth::id() !== $reservation->participant->user_id) {
            return response()->json(['message' => 'Non autorisé à envoyer les billets de cette réservation.'], 403);
        }
        // 2. Assurez-vous que la réservation est bien payée.
        if ($reservation->etat !== 'payée') {
            return response()->json(['message' => 'Cette réservation n\'a pas encore été payée.'], 400);
        }

        try {

            $billets = $reservation->billets()->with('zone', 'dateEvenement')->get();


            foreach ($billets as $billet) {
                $qrCodeData = $billet->QRcode;
                
                if (empty($qrCodeData)) {
                    Log::warning("QRcode est vide pour le billet ID: {$billet->idbillet}. Génération ignorée.");
                    $billet->qrCodeImage = ''; // Assigner une chaîne vide ou une image par défaut
                    continue;
                }

                // Générer le QR code en SVG (XML)
                $svg = DNS2D::getBarcodeSVG($qrCodeData, 'QRCODE');
                
                // Encoder le SVG en base64 pour l'incorporer dans l'HTML du PDF
                $billet->qrCodeImage = 'data:image/svg+xml;base64,' . base64_encode($svg);
            }
            
            $pdf = Pdf::loadView('emails.tickets_pdf_template', [
                'reservation' => $reservation,
                'billets' => $billets,
            ]);

            // Envoi de l'e-mail avec le PDF en pièce jointe
            Mail::to($reservation->participant->user->email)->send(new TicketsMail($reservation, $pdf->output()));

            Log::info("Billets envoyés par email pour la réservation ID: {$reservation->idreservation}");
            return response()->json(['message' => 'Vos billets ont été envoyés avec succès à votre adresse e-mail.']);

        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi des billets pour la réservation ID {$reservation->idreservation}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Une erreur est survenue lors de l\'envoi des billets. Veuillez réessayer ou contacter le support.'], 500);
        }
    }

    /**
     * Génère et télécharge un PDF des billets de la réservation.
     * @param \App\Models\Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function downloadTicketsPdf(Reservation $reservation)
    {
        // VÉRIFICATIONS DE SÉCURITÉ :
        if (Auth::check() && Auth::id() !== $reservation->participant->user_id) {
            abort(403, 'Non autorisé à télécharger les billets de cette réservation.');
        }
        if ($reservation->etat !== 'payée') {
            abort(400, 'Cette réservation n\'a pas encore été payée.');
        }

        try {
            
            $billets = $reservation->billets()->with('zone', 'dateEvenement')->get();


            foreach ($billets as $billet) {
                $qrCodeData = $billet->QRcode;

                if (empty($qrCodeData)) {
                    Log::warning("QRcode est vide pour le billet ID: {$billet->idbillet}. Génération ignorée.");
                    $billet->qrCodeImage = '';
                    continue;
                }
                
                $svg = DNS2D::getBarcodeSVG($qrCodeData, 'QRCODE');
                $billet->qrCodeImage = 'data:image/svg+xml;base64,' . base64_encode($svg);
            }

        // Génération du PDF avec les QR codes
        $pdf = Pdf::loadView('emails.tickets_pdf_template', [
            'reservation' => $reservation,
            'billets' => $billets,
        ]);

        Log::info("PDF des billets généré pour la réservation ID: {$reservation->idreservation}");

        return $pdf->download("billets_reservation_{$reservation->idreservation}.pdf");

    } catch (\Exception $e) {
            Log::error("Erreur lors du téléchargement des billets PDF pour la réservation ID {$reservation->idreservation}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500, 'Une erreur est survenue lors de la génération du PDF des billets.');
        }
    }

    //
    /**
     * Affiche la page de sélection de catégorie pour un événement
     *
     * @param \App\Models\Event $evenement
     * @return \Inertia\Response
     */
    
    public function createCategorySelection(Event $evenement)
    {
        //--- Étape 1: Débogage pour voir l'ID reçu
    Log::info('ID de l\'événement reçu dans createCategorySelection:', [
        'idevenement' => $evenement->idevenement,
        'type_placement' => $evenement->typeplacement
    ]);
    
        // Étape 2 : Récupérer TOUTES les dates futures disponibles pour cet événement 
        $datesDisponibles = $evenement->dates()
            ->where('datedebut', '>=', now())
            ->orderBy('datedebut')
            ->get(); 

        if ($datesDisponibles->isEmpty()) {
            return redirect()->back()->with('error', 'Aucune date future disponible pour cet événement.');
}
        // Pour l'affichage initial et pour passer un 'date_id' par défaut au formulaire Vue.js,
        // on va choisir la première date disponible comme date par défaut.
        $dateParDefaut = $datesDisponibles->first();
        
        if ($evenement->typeplacement == 'Libre') {
        
        // --- Étape 3: Récupérer les zones tarifaires pour la date PAR DÉFAUT ---
        // Les zones sont liées au lieu de la date, donc on utilise le lieu de notre date par défaut.
        $zones = ZoneTarifaire::where('idlieu', $dateParDefaut->idlieu)->get();
        
            // Calculer les places disponibles pour chaque zone
            $zones = $zones->map(function ($zone) use ($dateParDefaut,$evenement) {
                $billetsVendus = Billet::where('idzone', $zone->idzone)
                                ->where('idevenement', $evenement->idevenement)
                                ->where('iddate', $dateParDefaut->iddate)
                                ->count();
                $zone->places_disponibles = $zone->capacite - $billetsVendus;
                return $zone;
            });

            // --- Étape 4 : Envoyer les données au composant Vue.js ---
            return Inertia::render('Reservation/CategorySelection', [
        'evenement' => [
            'idevenement' => $evenement->idevenement,
            'titre' => $evenement->titre,
            'image' => $evenement->bandeannonce ? asset('storage/events-photos/' . $evenement->bandeannonce) : null,
            'prix' => $evenement->prixbase,
            'type_placement' => $evenement->typeplacement,
            // On passe l'ID de la date par défaut pour l'initialisation du formulaire Vue.js
            'date_id' => $dateParDefaut->iddate
        ],
        'zones' => $zones, // Les zones calculées pour la date par défaut
        'datesDisponibles' => $datesDisponibles->map(function($date) {
            return [
                'iddate' => $date->iddate,
                'datedebut' => $date->datedebut->format('d/m/Y H:i'), // Formatter pour l'affichage
                'nomlieu' => $date->lieu->nomlieu
            ];
        }),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register.participant'), 
            
    ]);
        
        }
        elseif ($evenement->typeplacement == 'Numéroté') {
            // Rediriger vers la page de réservation numérotée, a ajouté ultérieurement
        return redirect()->back()->with('info', 'Ce type d\'événement n\'est pas encore pris en charge pour la réservation numérotée.');

        }
    }

    /**
     * Récupère les zones tarifaires et leurs disponibilités pour une date d'événement spécifique.
     * Cette méthode sera appelée via AJAX depuis le frontend.
     *
     * @param \App\Models\Event $evenement L'événement (Route Model Binding)
     * @param \App\Models\DateEvenement $date La date spécifique (Route Model Binding)
     * @return \Illuminate\Http\JsonResponse Un JSON contenant les zones et leurs disponibilités
     */
    public function getZonesForDate(Event $evenement, DateEvenement $date)
    {
        if ($date->idevenement !== $evenement->idevenement) {
            return response()->json(['error' => 'Date not found for this event.'], 404);
        }

        // Récupérer les zones tarifaires pour le lieu de cette date
        $zones = ZoneTarifaire::where('idlieu', $date->idlieu)->get();
        
        // Si aucune zone n'est trouvée (ce qui ne devrait pas arriver selon le CdC),
        // on retourne une collection vide.
        if ($zones->isEmpty()) {
            return response()->json([]); // Retourne un tableau vide de zones.
        }

            // Calculer les places disponibles pour chaque zone pour CETTE DATE SPÉCIFIQUE
            $zones = $zones->map(function ($zone) use ($evenement, $date) {
                $billetsVendus = Billet::where('idzone', $zone->idzone)
                                       ->where('idevenement', $evenement->idevenement)
                                       ->where('iddate', $date->iddate) 
                                       ->count();
                $zone->places_disponibles = $zone->capacite - $billetsVendus;
                return $zone;
            });
        

        // Retourne les zones sous forme de réponse JSON
        return response()->json($zones);
    }

    /**
     * Enregistre une nouvelle réservation
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Étape 1 : Valider les données reçues (sécurité !)
        $request->validate([
            'evenement_id' => 'required|exists:evenements,idevenement',
            'date_id' => 'required|exists:date_evenements,iddate',
            'tickets' => 'required|array',
            'tickets.*.zone_id' => 'required|integer|exists:zone_tarifaire,idzone',
            'tickets.*.quantity' => 'required|integer|min:0',
            'type' => 'required|in:Libre,Numéroté'
        ]);

        // --- Étape 2 : Filtrer les tickets et vérifier si des tickets ont été sélectionnés
        $ticketsToSave = array_filter($request->tickets, function($ticket) {
            return $ticket['quantity'] > 0;
        });

        if (empty($ticketsToSave)) {
            return back()->withErrors(['tickets' => 'Vous devez sélectionner au moins un ticket.']);
        }

        // --- Étape 3 : Vérifier l'utilisateur et le participant
        // On vérifie si un utilisateur est connecté et on trouve ou crée son profil de 'Participant'.
        $participant = null;
        if (Auth::check()) { // Si un utilisateur est connecté
            // ---  firstOrCreate est utilisé pour créer le participant s'il n'existe pas ---
            $participant = Participant::firstOrCreate(
                    ['user_id' => Auth::id()], // Cherche un participant avec cet user_id
                    [] // Si non trouvé, crée un participant sans autres attributs pour l'instant
                );

        } else {
            // rediriger vers la page de connexion si l'utilisateur n'est pas connecté
            return redirect()->route('login')->with('message', 'Veuillez vous connecter pour réserver des billets.');
        }

        // --- Étape 4 : Démarrer une transaction de base de données (SÉCURITÉ !)
        // Cela assure que toutes les opérations suivantes (création de réservation, création de billets)
        // se font d'un bloc. Soit tout réussit et est enregistré, soit tout échoue et est annulé.
        DB::beginTransaction();

        try {
            // --- Étape 5 : Créer l'enregistrement de la réservation globale
            $reservation = new Reservation();
            $reservation->idparticipant = $participant->idParticipant;
            $reservation->datereservation = now();
            $reservation->etat = 'en attente'; // La réservation est 'en attente' avant le paiement
            $reservation->save();

            // On récupère les objets Event et DateEvenement complets pour travailler avec leurs propriétés.
            $evenement = Event::findOrFail($request->evenement_id);
            $dateEvenement = DateEvenement::findOrFail($request->date_id);

            // --- Étape 6 : Créer les billets individuels pour chaque zone et quantité
            foreach ($ticketsToSave as $ticket) {
                $zone = ZoneTarifaire::findOrFail($ticket['zone_id']);
                
                // --- Étape 6.1 : Vérification de disponibilité de dernière minute
                // On revérifie la disponibilité juste avant de créer les billets.
                // Cela évite les problèmes si plusieurs personnes réservent en même temps.
                $billetsVendus = Billet::where('idzone', $zone->idzone)
                                       ->where('idevenement', $evenement->idevenement)
                                       ->where('iddate', $dateEvenement->iddate) 
                                       ->count();
                $placesDisponibles = $zone->capacite - $billetsVendus;
                
                if ($placesDisponibles < $ticket['quantity']) {
                    throw new \Exception("Plus assez de places disponibles dans la zone {$zone->nomzone}.");
                }
                
                // --- Étape 6.2 : Création de chaque billet individuel
                for ($i = 0; $i < $ticket['quantity']; $i++) {
                    $billet = new Billet();
                    $billet->idevenement = $evenement->idevenement;
                    $billet->idzone = $zone->idzone;
                    $billet->idparticipant = $participant->idParticipant;
                    $billet->idreservation = $reservation->idreservation;
                    $billet->iddate = $dateEvenement->iddate;
                    $billet->estscanne = false;// Par défaut, le billet n'est pas encore scanné
                    $billet->QRcode = Billet::generateQRCode();// On génère un QR code unique pour ce billet
                    $billet->save();// On enregistre le billet en base de données
                }
                
                // --- Étape 6.3 : Mettre à jour le nombre de places vendues pour cette date d'événement
                $dateEvenement->placesvendues += $ticket['quantity'];
                $dateEvenement->save();
            }

            // --- Étape 7 : Confirmer la transaction , si tout est OK !
            DB::commit();
            Log::info('Transaction terminée, redirection vers payment.create avec ID:', ['reservation_id' => $reservation->idreservation]);

            Log::info('Redirection vers paiement pour réservation ID:', ['id' => $reservation->idreservation]);
            // --- Étape 8 : Rediriger vers la page de paiement
            return redirect()->route('payment.create', $reservation->idreservation);
        
        } catch (\Exception $e) {
            // --- Étape 9 : Annuler la transaction en cas d'erreur
            DB::rollBack();// Si une erreur survient n'importe où dans le bloc 'try', on annule tout.
            // On redirige l'utilisateur vers la page précédente avec un message d'erreur.
            return back()->withErrors(['error' => $e->getMessage()])
                         ->withInput($request->all()); // Garde les données du formulaire pour éviter de tout resaisir
        }
    }
}