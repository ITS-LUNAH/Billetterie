<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

// Contrôleurs Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganisateurController;
use App\Http\Controllers\Admin\EvenementController as AdminEvenementController;
use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\CommissionController;

// Contrôleurs Organisateur
use App\Http\Controllers\Organisateur\DashboardController as OrganisateurDashboardController;
use App\Http\Controllers\Organisateur\EvenementController as OrganisateurEvenementController;

// Contrôleurs généraux
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;

use App\Models\Event;
use Carbon\Carbon;

// --- Routes accessibles UNIQUEMENT aux utilisateurs non connectés (Guest) ---
Route::middleware('guest')->group(function () {
    
    Route::get('/register/participant', function () {
        return Inertia::render('Auth/Register', ['userTypeProp' => 'Participant']);
    })->name('register.participant');

    Route::get('/register/organisateur', function () {
        return Inertia::
        render('Auth/Register', ['userTypeProp' => 'Organisateur']);
    })->name('register.organisateur');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

   });

   Route::get('/test', function () {
    return Inertia::render('test', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),]);
   });

// --- Route d'accueil publique (accessible à tous) ---
Route::get('/', function () {
    // Récupérer les événements à venir (estValide = true)
    $upcomingEvents = Event::where('estValide', true)
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

/* // Nouvelle route pour la sélection de catégorie pour les événements en placement libre
Route::get('/reservation/{evenement}/categories', [ReservationController::class, 'createCategorySelection'])
     ->name('reservation.category.create'); */

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
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Organisateurs
        Route::get('/organisateurs', [OrganisateurController::class, 'index'])->name('organisateurs.index');
        Route::resource('organisateurs', OrganisateurController::class)->except(['create', 'store', 'show']);
        Route::post('/organisateurs/{id}/toggle-activation', [OrganisateurController::class, 'toggleActivation'])->name('admin.organisateurs.toggleActivation');
        Route::post('/organisateurs/{id}/valider', [OrganisateurController::class, 'valider'])->name('organisateurs.valider');
        Route::post('/organisateurs/{id}/suspendre', [OrganisateurController::class, 'suspendre'])->name('organisateurs.suspendre');
        Route::post('/organisateurs/{id}/reactiver', [OrganisateurController::class, 'reactiver'])->name('organisateurs.reactiver');
        Route::post('/organisateurs/{id}/refuser', [OrganisateurController::class, 'refuser'])->name('organisateurs.refuser'); // New route for refusing validation

        // Événements
        Route::get('/evenements', [AdminEvenementController::class, 'index'])->name('evenements.index');
        // Le 'show' est souvent omis si vous utilisez une modale pour les détails
        Route::resource('evenements', App\Http\Controllers\Admin\EvenementController::class)->except(['show']);
        Route::post('/evenements/{id}/valider', [AdminEvenementController::class, 'valider'])->name('evenements.valider');
        Route::post('/evenements/{id}/refuser', [AdminEvenementController::class, 'refuser'])->name('evenements.refuser');
        Route::post('evenements/{id}/toggle-activation', [App\Http\Controllers\Admin\EvenementController::class, 'toggleActivation'])->name('evenements.toggleActivation');


        // Statistiques
        Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques.index');

        // Participants
        Route::resource('participants', App\Http\Controllers\Admin\ParticipantController::class)->except(['create', 'store']);
        Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');

        // Commission
        Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');
        Route::get('/commissions/create', [CommissionController::class, 'create'])->name('commissions.create');
        Route::post('/commissions', [CommissionController::class, 'store'])->name('commissions.store');
        Route::delete('/commissions/{id}', [CommissionController::class, 'destroy'])->name('commissions.destroy');
        
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

// Routes pour la réservation
Route::get('/reservation/{evenement}/categories', [ReservationController::class, 'createCategorySelection'])
     ->name('reservation.category.create');
Route::post('/reservation', [ReservationController::class, 'store'])
     ->name('reservation.store');

// Routes pour le paiement
Route::get('/payment/{reservation}', [PaymentController::class, 'create'])
     ->name('payment.create');
Route::post('/payment/{reservation}', [PaymentController::class, 'store'])
     ->name('payment.store');

// Route de confirmation (à implémenter plus tard)
Route::get('/reservation/{reservation}/confirmation', function($reservation) {
    return Inertia::render('Reservation/Confirmation', [
        'reservation' => $reservation
    ]);
})->name('reservation.confirmation');
