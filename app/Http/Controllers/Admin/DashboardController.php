<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billet;
use App\Models\Reservation;
use App\Models\Evenement;
use App\Models\Organisateur;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Statistiques pour les cartes du dashboard
            $stats = [
                [
                    'title' => 'Billets Vendus',
                    'value' => Billet::count(),
                    'icon' => 'confirmation_number',
                    'trend' => '+81%'
                ],
                [
                    'title' => 'Revenu Total',
                    'value' => number_format(Billet::sum('prix'), 0, ',', ' ') . ' DH',
                    'icon' => 'trending_up',
                    'trend' => '+62%'
                ],
                [
                    'title' => 'Réservations',
                    'value' => Reservation::count(),
                    'icon' => 'event',
                    'trend' => '+44%'
                ],
                [
                    'title' => 'Organisateurs',
                    'value' => Organisateur::where('estValide', true)->count(),
                    'icon' => 'group',
                    'trend' => '+12%'
                ]
            ];

            // Réservations récentes (dernières 10)
            $recentReservations = Reservation::with(['user', 'evenement'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($reservation) {
                    return (object) [
                        'id' => $reservation->id,
                        'event' => (object) ['titre' => $reservation->evenement->titre ?? 'N/A'],
                        'user' => (object) ['name' => $reservation->user->name ?? 'N/A'],
                        'created_at' => $reservation->created_at,
                        'status' => $reservation->status ?? 'en_attente'
                    ];
                });

            return view('admin.dashboard', compact('stats', 'recentReservations'));

        } catch (\Exception $e) {
            // En cas d'erreur, passer des données par défaut
            $stats = [
                [
                    'title' => 'Billets Vendus',
                    'value' => '0',
                    'icon' => 'confirmation_number',
                    'trend' => '0%'
                ],
                [
                    'title' => 'Revenu Total',
                    'value' => '0 DH',
                    'icon' => 'trending_up',
                    'trend' => '0%'
                ],
                [
                    'title' => 'Réservations',
                    'value' => '0',
                    'icon' => 'event',
                    'trend' => '0%'
                ]
            ];

            $recentReservations = collect();

            return view('admin.dashboard', compact('stats', 'recentReservations'))
                ->with('error', 'Erreur lors du chargement des données: ' . $e->getMessage());
        }
    }
}