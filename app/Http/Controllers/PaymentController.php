<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Billet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe; 
use Stripe\Checkout\Session; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Initialise la clé secrète de Stripe. C'est fait une seule fois lors de la création du contrôleur.
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Affiche la page de paiement et initie une session Stripe Checkout.
     * Cette méthode sera appelée après la création de la réservation.
     *
     * @param \App\Models\Reservation $reservation La réservation pour laquelle le paiement est effectué.
     * @return \Inertia\Response
     */
    public function create(Reservation $reservation)
    {
        Log::info('Tentative de création de session de paiement pour la réservation:', ['id' => $reservation->idreservation]);

        // ---Partie 1. Calcule du montant total de la réservation ---

        // On récupère tous les billets liés à cette réservation
        $billets = Billet::where('idreservation', $reservation->idreservation)->get();

        if ($billets->isEmpty()) {
            throw new \Exception('Aucun billet trouvé pour cette réservation. Impossible de procéder au paiement.');
        }

        $line_items = [];
        $totalAmount = 0; // Calcul du montant total à envoyer à Stripe

        // Regrouper les billets par zone pour éviter les doublons dans les line_items de Stripe
        $groupedBillets = $billets->groupBy('idzone');

        foreach ($groupedBillets as $zoneId => $zoneBillets) {
            $firstBilletInZone = $zoneBillets->first();

            // VÉRIFICATION : S'assurer que la zone existe via la relation
            $zone = $firstBilletInZone->zone;// La relation 'zone' dans le modèle Billet.

            if (!$zone) { // Si la zone est null (relation n'a rien trouvé)
                Log::warning('ZoneTarifaire introuvable pour idzone:', ['idzone' => $zoneId, 'idreservation' => $reservation->idreservation]);
                throw new \Exception('Erreur: Une zone tarifaire associée à un billet (ID: ' . $zoneId . ') est introuvable. Contactez le support.');
            }
            
            $quantity = $zoneBillets->count();
            $pricePerTicket = $zone->tarif; // Utilise le tarif de la zone

            $line_items[] = [
                'price_data' => [
                    'currency' => 'mad', // Devise : dirhams marocains
                    'unit_amount' => (int)($pricePerTicket * 100), // Montant en centimes (Stripe attend des entiers)
                    'product_data' => [
                        'name' => 'Billet ' . $zone->nomzone, // Nom du produit sur la page de paiement Stripe
                    ],
                ],
                'quantity' => $quantity,
            ];
            $totalAmount += $pricePerTicket * $quantity;
        }

        // Si le calcul aboutit à 0, il y a un problème
        if ($totalAmount <= 0) {
            // montant total invalide.
            throw new \Exception('Le montant total de la réservation est invalide (zéro ou négatif). Contactez le support.');
        }

        try {
            // --- Etape 2. Créer une session Stripe Checkout ---
            $checkoutSession = Session::create([
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('payment.success', ['reservation' => $reservation->idreservation]),
                'cancel_url' => route('payment.cancel', ['reservation' => $reservation->idreservation]),
                'metadata' => [
                    'idreservation' => $reservation->idreservation, // Utile pour identifier la réservation via les webhooks
                ],
                // Optionnel: Pré-remplir l'e-mail de l'utilisateur s'il est connecté
                //'customer_email' => 'user@gmail.com' ?? null,
            ]);

            Log::info('Session Stripe créée avec succès:', ['sessionId' => $checkoutSession->id]);

            // Rediriger l'utilisateur vers la page de paiement Stripe Checkout, Au lieu de rendre une vue Inertia, on passe l'ID de la session à la vue, C'est le frontend qui fera la redirection vers Stripe.
            return Inertia::render('Payment/PaymentPage', [
                'sessionId' => $checkoutSession->id,
                'stripePublishableKey' => env('STRIPE_KEY'),
            ]);

        } catch (\Exception $e) {// Ce bloc catchera toutes les exceptions levées dans la méthode,
            Log::error('Erreur lors de la création de la session Stripe:', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->with('error', 'Erreur lors de l\'initialisation du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Gère la redirection après un paiement réussi.
     * @param \App\Models\Reservation $reservation
     * @return \Inertia\Response
     */
    public function success(Reservation $reservation)
    {
        // Ici avant de passer en mode production, il faut vérifier le statut de la session Stripe via un webhook pour confirmer le paiement avant de mettre à jour la base de données.
        $reservation->etat = 'payée';
        $reservation->save();
        Log::info('Réservation marquée comme payée suite à succès Stripe:', ['id' => $reservation->idreservation]);

        return Inertia::render('Payment/PaymentStatus', [
            'status' => 'success',
            'message' => 'Votre paiement a été effectué avec succès et votre réservation est confirmée !',
            'reservation' => $reservation->toArray() // Passer les détails de la réservation
        ]);
    }

    /**
     * Gère la redirection après un paiement annulé ou échoué.
     * @param \App\Models\Reservation $reservation
     * @return \Inertia\Response
     */
    public function cancel(Reservation $reservation)
    {
        // Ici, on ne change pas l'état de la réservation, car elle reste "en attente" et apres une durée précise il va être supprimé.
        Log::info('Paiement annulé pour la réservation:', ['id' => $reservation->idreservation]);

        return Inertia::render('Payment/PaymentStatus', [
            'status' => 'cancelled',
            'message' => 'Votre paiement a été annulé. Vous pouvez réessayer ou contacter le support.',
            'reservation' => $reservation->toArray()
        ]);
    }
}
