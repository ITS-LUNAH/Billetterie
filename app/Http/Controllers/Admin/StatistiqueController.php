<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Reservation;
use App\Models\Evenement;

class StatistiqueController extends Controller
{
    public function index()
    {
        $totalBillets = Billet::count();
        $revenuTotal = Billet::sum('prix');
        $totalReservations = Reservation::count();

        // Charger les stats par événement
        $statsParEvenement = Evenement::withCount('billets')
            ->withSum('billets', 'prix')
            ->get();

        return view('admin.statistiques.index', compact(
            'totalBillets',
            'revenuTotal',
            'totalReservations',
            'statsParEvenement'
        ));
    }
}
