<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia; // Importez la façade Inertia

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administrateur.
     */
    public function index()
    {
        // Retourne un composant Inertia (votre composant Vue)
        return Inertia::render('Admin/Dashboard', [
            // Vous pouvez passer des props (données) à votre composant Vue ici
            'message' => 'Bienvenue, Admin ! Vous êtes connecté en tant qu\'administrateur.',
            // Par exemple: 'usersCount' => \App\Models\User::count(),
        ]);
    }
};