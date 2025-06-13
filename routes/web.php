<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganisateurController;
use App\Http\Controllers\Admin\EvenementController as AdminEvenementController;
use App\Http\Controllers\Admin\StatistiqueController;

use App\Http\Controllers\Organisateur\DashboardController as OrganisateurDashboardController;
use App\Http\Controllers\Organisateur\EvenementController as OrganisateurEvenementController;

use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

use App\Models\Evenement;
use Carbon\Carbon;

// --- Routes accessibles UNIQUEMENT aux utilisateurs non connectés (Guest) ---
Route::middleware('guest')->group(function () {
    
    Route::get('/register/participant', function () {
        return Inertia::render('Auth/Register', ['userTypeProp' => 'Participant']);
    })->name('register.participant');

    Route::get('/register/organisateur', function () {
        return Inertia::render('Auth/Register', ['userTypeProp' => 'Organisateur']);
    })->name('register.organisateur');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

   });

// --- Route d'accueil publique (accessible à tous) ---
Route::get('/', function () {
    // Récupérer les événements à venir (estValide = true)
    $upcomingEvents = Evenement::where('estValide', true)
        ->with(['dates' => function($query) {
            $query->where('dateDebut', '>=', Carbon::today())
                  ->orderBy('dateDebut', 'asc');
        }, 'dates.lieu']) // Chargez les dates et les lieux liés à ces dates
        ->has('dates') // N'incluez que les événements qui ont au moins une date future valide
        ->limit(10) // Limitez à 10 événements comme dans l'exemple
        ->get()
        ->map(function ($event) {
            // Formater les données pour le frontend
            $firstUpcomingDate = $event->dates->first(); // Récupérer la première date à venir
            return [
                'id' => $event->idevenement,
                'titre' => $event->titre,
                'description' => $event->description,
                'image' => $event->bandeannonce ? asset('storage/events-photos/'. $event->bandeannonce) : null, // Assurez-vous que l'image est accessible via storage:link
                'prix' => $event->prixbase,
                'lieu' => $firstUpcomingDate->lieu->ville . ' - ' . $firstUpcomingDate->lieu->adresse, // Concaténer ville et adresse
                'date' => $firstUpcomingDate->datedebut->translatedFormat('d M Y') . ' à ' . Carbon::parse($firstUpcomingDate->heuredebut)->format('H:i'), // Formater la date et l'heure
                // Ajoutez d'autres infos si nécessaire pour les cartes
            ];
        });


    return Inertia::render('Accueil', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'upcomingEvents' => $upcomingEvents, // Passez les événements à la vue

    ]);
});

//--- Pages publiques ---
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/evenement/{event}', [EventController::class, 'show'])->name('evenement.show');

// Nouvelle route pour la sélection de catégorie pour les événements en placement libre
Route::get('/reservation/{evenement}/categories', [ReservationController::class, 'createCategorySelection'])
     ->name('reservation.category.create');

// Route pour soumettre les réservations (utilisée par les deux types de placement)
Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');


// --- Routes accessibles UNIQUEMENT aux utilisateurs connectés (Authentifiés) ---
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Cette route est le point d'entrée par défaut après la connexion.
    Route::get('/dashboard', function () {

        if (auth()->check()) {
            $userType = auth()->user()->typeUser; 
            if ($userType === 'Administrateur') { 
                return redirect()->route('admin.dashboard'); // Redirection vers le dashboard admin
            }
            else if ($userType === 'Organisateur') {
                 return redirect()->route('organisateur.dashboard');
             }
             /* else if ($userType === 'participant') {
                 return redirect()->route('participant.dashboard');
             } */
        }
        // Si aucun type spécifique n'est trouvé ou si vous voulez un fallback général
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // --- Zone Admin ---
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Organisateurs
        Route::get('/organisateurs', [OrganisateurController::class, 'index'])->name('organisateurs.index');
        Route::post('/organisateurs/{id}/valider', [OrganisateurController::class, 'valider'])->name('organisateurs.valider');
        Route::post('/organisateurs/{id}/suspendre', [OrganisateurController::class, 'suspendre'])->name('organisateurs.suspendre');
        Route::post('/organisateurs/{id}/reactiver', [OrganisateurController::class, 'reactiver'])->name('organisateurs.reactiver');

        // Événements
        Route::get('/evenements', [AdminEvenementController::class, 'index'])->name('evenements.index');
        Route::post('/evenements/{id}/valider', [AdminEvenementController::class, 'valider'])->name('evenements.valider');
        Route::post('/evenements/{id}/refuser', [AdminEvenementController::class, 'refuser'])->name('evenements.refuser');

        // Statistiques
        Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques.index');
    });

    // --- Zone Organisateur ---
    Route::middleware('organisateur')->prefix('organisateur')->name('organisateur.')->group(function () {
        Route::get('/dashboard', [OrganisateurDashboardController::class, 'index'])->name('dashboard');

        // Gestion événements
        Route::get('/evenements/create', [OrganisateurEvenementController::class, 'create'])->name('evenements.create');
        Route::post('/evenements', [OrganisateurEvenementController::class, 'store'])->name('evenements.store');
        Route::get('/evenements', [OrganisateurEvenementController::class, 'index'])->name('evenements.index');
        Route::get('/evenements/{id}/edit', [OrganisateurEvenementController::class, 'edit'])->name('evenements.edit');
        Route::put('/evenements/{id}', [OrganisateurEvenementController::class, 'update'])->name('evenements.update');
        Route::delete('/evenements/{id}', [OrganisateurEvenementController::class, 'destroy'])->name('evenements.destroy');
    });
});
