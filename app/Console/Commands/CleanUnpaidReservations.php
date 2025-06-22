<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\Billet;
use App\Models\DateEvenement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanUnpaidReservations extends Command
{
    /**
     * Le nom de la commande. C'est ce que vous taperez pour l'exécuter.
     * @var string
     */
    protected $signature = 'reservations:clean-unpaid';

    /**
     * Une courte description de ce que fait la commande.
     * @var string
     */
    protected $description = 'Supprime les réservations non payées et leurs billets associés.';

    /**
     * Exécute la logique de la commande.
     */
    public function handle()
    {
        // 1. Délai d'expiration : Les réservations de plus de 10 minutes non payées
        $expirationTime = Carbon::now()->subMinutes(1); // Vous pouvez changer 10 en moins pour tester

        // 2. Trouver les réservations à supprimer :
        // On cherche les réservations qui sont "en attente" DE PAIEMENT
        // ET qui ont été créées il y a plus de 10 minutes.
        $unpaidReservations = Reservation::where('etat', 'en attente')
                                         ->where('created_at', '<', $expirationTime)
                                         ->get();

        if ($unpaidReservations->isEmpty()) {
            $this->info('Aucune réservation non payée à nettoyer pour le moment.');
            return Command::SUCCESS;
        }

        $this->info('Début du nettoyage de ' . $unpaidReservations->count() . ' réservations non payées...');

        foreach ($unpaidReservations as $reservation) {
            DB::beginTransaction(); // Démarre une transaction pour que tout soit fait ou annulé
            try {
                $reservationId = $reservation->idreservation;

                // Avertissement : Assurez-vous que la relation 'zone' est correcte dans Billet.php
                // Sinon, la mise à jour des places peut échouer si un billet a une zone invalide.

                // Récupérer et mettre à jour les places dans DateEvenement avant de supprimer les billets
                $billetsForReservation = Billet::where('idreservation', $reservationId)->get();
                foreach ($billetsForReservation->groupBy('iddate') as $dateId => $groupedBillets) {
                    $dateEvenement = DateEvenement::find($dateId);
                    if ($dateEvenement) {
                        $quantityToSubtract = $groupedBillets->count();
                        // S'assurer que ça ne descend pas en dessous de zéro
                        $dateEvenement->placesvendues = max(0, $dateEvenement->placesvendues - $quantityToSubtract);
                        $dateEvenement->save();
                        Log::info("Places remises en stock pour DateEvenement ID {$dateId} suite à nettoyage (Réservation {$reservationId}).");
                    }
                }

                // Supprimer tous les billets associés à cette réservation
                Billet::where('idreservation', $reservationId)->delete();
                
                // Supprimer la réservation elle-même
                $reservation->delete();

                DB::commit(); // Tout s'est bien passé, valide les changements
                $this->info("Réservation ID {$reservationId} supprimée avec succès.");

            } catch (\Exception $e) {
                DB::rollBack(); // Quelque chose a mal tourné, annule tous les changements
                $this->error("Erreur lors du nettoyage de la réservation ID {$reservationId}: " . $e->getMessage());
                Log::error("Erreur nettoyage réservation ID {$reservationId}:", [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        }

        $this->info('Nettoyage des réservations non payées terminé.');
        return Command::SUCCESS;
    }
}
