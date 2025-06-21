<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord admin
     */
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'title' => 'Tableau de bord',
            'stats' => [
                ['title' => 'Utilisateurs', 'value' => '1,234', 'icon' => 'users', 'trend' => '12%', 'trendDirection' => 'up'],
                ['title' => 'Événements', 'value' => '56', 'icon' => 'calendar', 'trend' => '5%', 'trendDirection' => 'up'],
                ['title' => 'Revenus', 'value' => '$8,540', 'icon' => 'dollar-sign', 'trend' => '3.2%', 'trendDirection' => 'down'],
                ['title' => 'Taux de conversion', 'value' => '3.6%', 'icon' => 'trending-up', 'trend' => '0.4%', 'trendDirection' => 'up']
            ],
            'activities' => [
                ['id' => 1, 'user' => 'Jean Dupont', 'action' => 'a créé un événement', 'time' => 'Il y a 10 minutes'],
                ['id' => 2, 'user' => 'Marie Martin', 'action' => 'a mis à jour son profil', 'time' => 'Il y a 25 minutes'],
                ['id' => 3, 'user' => 'Admin', 'action' => 'a supprimé un commentaire', 'time' => 'Il y a 1 heure'],
                ['id' => 4, 'user' => 'Pierre Lambert', 'action' => 's\'est inscrit', 'time' => 'Il y a 2 heures']
            ]
        ]);
    }

    /**
     * Afficher la page des utilisateurs
     */
    public function users()
    {
        return Inertia::render('Admin/Users', [
            'title' => 'Gestion des utilisateurs'
        ]);
    }

    /**
     * Afficher la page des événements
     */
    public function events()
    {
        return Inertia::render('Admin/Events', [
            'title' => 'Gestion des événements'
        ]);
    }

    /**
     * Afficher la page des statistiques
     */
    public function statistics()
    {
        return Inertia::render('Admin/Statistics', [
            'title' => 'Statistiques'
        ]);
    }
}
