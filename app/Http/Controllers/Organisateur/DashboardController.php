<?php
namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Organisateur;
use App\Models\Event;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Importer la façade DB

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Trouver l'organisateur lié à l'utilisateur connecté
        $organisateur = Organisateur::where('user_id', $user->id)->first();

        // Vérifier si l'organisateur existe
        if (!$organisateur) {
            return Inertia::render('Organisateur/Dashboard', [
                'user' => $user,
                'statsData' => [
                    'active_events' => 0,
                    'total_participants' => 0,
                    'monthly_revenue' => 0,
                    'average_rating' => 'N/A',
                ],
                'recentActivity' => [],
                'totalBillets' => 0,
                'revenuTotal' => 0,
                'totalReservations' => 0,
                'statsParEvenement' => [],
                'topEvenements' => [],
                'statsParMois' => [],
                'message' => 'Organisateur non trouvé.'
            ]);
        }

        // Événements créés par cet organisateur
        $evenements = Event::where('idorganisateur', $organisateur->idOrganisateur)->get();
        $eventIds = $evenements->pluck('idevenement');

        // --- Statistiques générales ---
        $activeEventsCount = $evenements->where('estvalide', true)->where('estActif', true)->count();

        // Total participants (nombre total de réservations)
        $totalReservations = Reservation::whereIn('idevenement', $eventIds)->count();
        $totalBillets = $totalReservations; // Si 1 réservation = 1 billet, sinon ajuster si une réservation peut avoir plusieurs billets

        $revenuTotal = Reservation::whereIn('idevenement', $eventIds)->sum('montant_paye');

        // Revenus mensuels (nombre de réservations pour le mois en cours)
        $monthlyRevenueCount = Reservation::whereIn('idevenement', $eventIds)
                                     ->whereYear('created_at', Carbon::now()->year)
                                     ->whereMonth('created_at', Carbon::now()->month)
                                     ->count();

        $averageRating = 'N/A'; //n'est pas appliqué

        // --- Statistiques par événement ---
        $statsParEvenement = Event::where('evenements.idorganisateur', $organisateur->idOrganisateur)
            ->leftJoin('reservations', 'evenements.idevenement', '=', 'reservations.idevenement')
            ->select(
                'evenements.titre',
                DB::raw('COUNT(reservations.idreservation) as billets_count'),
                DB::raw('SUM(reservations.montant_paye) as revenu_total')
            )
            ->groupBy('evenements.idevenement', 'evenements.titre')
            ->orderByDesc('billets_count')
            ->get();

        // Top 5 Événements Populaires (basé sur les billets vendus)
        $topEvenements = $statsParEvenement->take(5);

        // --- Évolution des Ventes (12 derniers mois) ---
        $statsParMois = Reservation::whereIn('idevenement', $eventIds)
            ->select(
                DB::raw('YEAR(created_at) as annee'),
                DB::raw('MONTH(created_at) as mois'),
                DB::raw('COUNT(idreservation) as nombre_billets'),
                DB::raw('SUM(montant_paye) as revenu')
            )
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'asc')
            ->orderBy('mois', 'asc')
            ->get();


        // Activités récentes fictives
        $recentActivity = [
            [
                'date' => now()->format('d/m/Y'),
                'message' => 'Nouvel événement ajouté',
                'color' => 'indigo',
            ],
            [
                'date' => now()->subDays(1)->format('d/m/Y'),
                'message' => '2 réservations confirmées',
                'color' => 'green',
            ],
            [
                'date' => now()->subDays(3)->format('d/m/Y'),
                'message' => 'Mise à jour du profil',
                'color' => 'purple',
            ],
        ];

        return Inertia::render('Organisateur/Dashboard', [
            'user' => $user,
            'statsData' => [
                'active_events' => $activeEventsCount,
                'total_participants' => $totalReservations, // Renommé pour plus de clarté
                'monthly_revenue' => $monthlyRevenueCount, // C'est le COUNT, pas le montant
                'average_rating' => $averageRating,
            ],
            'recentActivity' => $recentActivity,
            'totalBillets' => $totalBillets,
            'revenuTotal' => $revenuTotal,
            'totalReservations' => $totalReservations,
            'statsParEvenement' => $statsParEvenement,
            'topEvenements' => $topEvenements,
            'statsParMois' => $statsParMois,
        ]);
    }
}
