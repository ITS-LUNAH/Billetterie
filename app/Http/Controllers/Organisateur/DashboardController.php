<?php

namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia; // Importez la façade Inertia

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'organisateur.
     */
    public function index()
    {
        // Retourne un composant Inertia (votre composant Vue)
        return Inertia::render('Organisateur/Dashboard', [
            // Vous pouvez passer des props (données) à votre composant Vue ici
            'message' => 'Bienvenue, Organisateur ! Vous êtes connecté en tant qu\'organisateur.',
            // Par exemple: 'usersCount' => \App\Models\User::count(),
        ]);

        //return view('organisateur.dashboard');
    }
};