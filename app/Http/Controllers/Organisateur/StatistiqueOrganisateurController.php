<?php
namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evenement;
use App\Models\Reservation;

class StatistiqueOrganisateurController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Organisateur connecté
        $organisateurId = $user->organisateur->idOrganisateur;

        // Nombre d'événements actifs (validés + actifs)
        $activeEvents = Evenement::where('idorganisateur', $organisateurId)
                          ->where('estvalide', true)
                          ->where('estActif', true)
                          ->count();

        // Total participants
        $eventIds = Evenement::where('idorganisateur', $organisateurId)->pluck('idevenement');
        $totalParticipants = Reservation::whereIn('idevenement', $eventIds)->count();

        // Revenus mensuels
        $currentMonth = now()->format('Y-m');
        $monthlyRevenue = Reservation::whereIn('idevenement', $eventIds)
                          ->where('created_at', 'like', "$currentMonth%")
                          ->sum('montant_paye');

        $averageRating = 4.2; 

        return response()->json([
            'active_events' => $activeEvents,
            'total_participants' => $totalParticipants,
            'monthly_revenue' => $monthlyRevenue,
            'average_rating' => $averageRating,
        ]);
    }
}
