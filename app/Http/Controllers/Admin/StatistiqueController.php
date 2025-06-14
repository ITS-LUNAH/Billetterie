<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Reservation;
use App\Models\Event;
use App\Models\ZoneTarifaire;
// use Inertia\Inertia; // Commenté car on utilise des vues Blade
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function index()
    {
        try {
            // Statistiques générales
            $totalBillets = Billet::count();
            $totalReservations = Reservation::count();
            
            // Calcul du revenu total basé sur les zones tarifaires
            $revenuTotal = Billet::join('zone_tarifaire', 'billets.idzone', '=', 'zone_tarifaire.idzone')
                ->sum('zone_tarifaire.tarif');

            // Alternative si vous avez une colonne prix directement dans billets
            // $revenuTotal = Billet::sum('prix');

            // Statistiques par événement avec jointures pour calculer le revenu
            $statsParEvenement = Event::select([
                    'evenements.idevenement',
                    'evenements.titre',
                    DB::raw('COUNT(billets.idbillet) as billets_count'),
                    DB::raw('COALESCE(SUM(zone_tarifaire.tarif), 0) as revenu_total')
                ])
                ->leftJoin('billets', 'evenements.idevenement', '=', 'billets.idevenement')
                ->leftJoin('zone_tarifaire', 'billets.idzone', '=', 'zone_tarifaire.idzone')
                ->groupBy('evenements.idevenement', 'evenements.titre')
                ->orderBy('revenu_total', 'desc')
                ->get();

            // Statistiques par mois (derniers 12 mois) - Fixed ambiguous column reference
            $statsParMois = Billet::select([
                    DB::raw('YEAR(billets.created_at) as annee'),
                    DB::raw('MONTH(billets.created_at) as mois'),
                    DB::raw('COUNT(*) as nombre_billets'),
                    DB::raw('SUM(zone_tarifaire.tarif) as revenu')
                ])
                ->join('zone_tarifaire', 'billets.idzone', '=', 'zone_tarifaire.idzone')
                ->where('billets.created_at', '>=', now()->subYear())
                ->groupBy('annee', 'mois')
                ->orderBy('annee', 'desc')
                ->orderBy('mois', 'desc')
                ->get();

            // Top 5 des événements les plus populaires - Fixed column name inconsistency
            $topEvenements = Event::select([
                    'evenements.idevenement',
                    'evenements.titre',
                    DB::raw('COUNT(billets.idbillet) as total_billets')
                ])
                ->leftJoin('billets', 'evenements.idevenement', '=', 'billets.idevenement')
                ->groupBy('evenements.idevenement', 'evenements.titre')
                ->orderBy('total_billets', 'desc')
                ->limit(5)
                ->get();

            return view('admin.statistiques.index', compact(
                'totalBillets', 
                'revenuTotal', 
                'totalReservations', 
                'statsParEvenement', 
                'statsParMois', 
                'topEvenements'
            ));

        } catch (\Exception $e) {
            // Pour déboguer, affichez l'erreur
            dd('Erreur: ' . $e->getMessage(), $e->getTraceAsString());
            
            // En production, vous pouvez rediriger avec un message d'erreur
            // return redirect()->back()->with('error', 'Erreur lors du chargement des statistiques');
        }
    }
}