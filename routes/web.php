<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
// Contrôleurs pour les participants
use App\Http\Controllers\Participant\DashboardController as ParticipantDashboardController;
use App\Http\Controllers\Participant\ReservationController as ParticipantReservationController;
use App\Http\Controllers\Participant\BilletController;
use App\Http\Controllers\Participant\EvenementController;
use App\Http\Controllers\Participant\ProfilController;

// Contrôleurs Admin
//use App\Http\Controllers\Organisateur\OrganisateurController;
use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganisateurController;
use App\Http\Controllers\Admin\EvenementController as AdminEvenementController;
//use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\CommissionController;

// Contrôleurs Organisateur
use App\Http\Controllers\Organisateur\DashboardController as OrganisateurDashboardController;
use App\Http\Controllers\Organisateur\EvenementController as OrganisateurEvenementController;
use  App\Http\Controllers\Organisateur\StatistiqueOrganisateurController;

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
})->name('accueil');


//--- Pages publiques ---
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/evenement', [EventController::class, 'index'])->name('evenement.index');
Route::get('/events/search', [EventController::class, 'search'])->name('events.search');
Route::get('/evenement/{event}', [EventController::class, 'show'])->name('evenement.show');

// Routes pour la réservation
Route::get('/reservation/{evenement}/categories', [ReservationController::class, 'createCategorySelection'])
     ->name('reservation.category.create');


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
             else if ($userType === 'Participant') {
                 return redirect()->route('participant.dashboard');
             }
        }
        // Si aucun type spécifique n'est trouvé ou si vous voulez un fallback général
        return Inertia::render('Dashboard');
    })->name('dashboard');


    // --- Zone Participant (SANS middleware personnalisé) ---
    Route::prefix('participant')->name('participant.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])->name('dashboard');
        
        // Réservations
        Route::get('/reservations', [ParticipantReservationController::class, 'index'])->name('reservations');
        Route::get('/reservations/{id}', [ParticipantReservationController::class, 'show'])->name('reservations.show');
        
        // Billets
        Route::get('/billets', [BilletController::class, 'index'])->name('billets');
        Route::get('/billets/{id}', [BilletController::class, 'show'])->name('billets.show');
        
        // Événements
        Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements');
        Route::get('/evenements/{id}', [EvenementController::class, 'show'])->name('evenements.show');
        
        // Profil
        Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
        Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
        
        // Paramètres et Support
        Route::get('/parametres', [ProfilController::class, 'parametres'])->name('parametres');
        Route::get('/support', [ProfilController::class, 'support'])->name('support');
    });

    // --- Zone Admin ---
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

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
        Route::put('/admin/evenements/{id}', [EvenementController::class, 'update'])->name('admin.evenements.update');
Route::post('/admin/evenements/{id}/toggle-validation', [EvenementController::class, 'toggleValidation'])->name('admin.evenements.toggleValidation');
        Route::resource('evenements', App\Http\Controllers\Admin\EvenementController::class)->except(['show']);
        Route::post('/evenements/{id}/valider', [AdminEvenementController::class, 'valider'])->name('evenements.valider');
        Route::post('/evenements/{id}/refuser', [AdminEvenementController::class, 'refuser'])->name('evenements.refuser');
        Route::post('evenements/{id}/toggle-validation', [App\Http\Controllers\Admin\EvenementController::class, 'toggleValidation'])->name('evenements.toggleValidation');


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

        Route::get('/api/stats', [App\Http\Controllers\Organisateur\DashboardController::class, 'getStats'])
            ->name('api.stats');
    
    // API pour récupérer l'activité récente (optionnel)
        Route::get('/api/activity', [App\Http\Controllers\Organisateur\DashboardController::class, 'getActivity'])
            ->name('api.activity');

        // Gestion événements
        Route::post('/organisateur/evenements', [OrganisateurEvenementController::class, 'store'])->name('organisateur.evenements.store');

        Route::middleware('auth:sanctum')->get('/organisateur/statistiques', [StatistiqueOrganisateurController::class, 'index']);

        Route::get('/evenements/create', [OrganisateurEvenementController::class, 'create'])->name('evenements.create');
        Route::post('/evenements', [OrganisateurEvenementController::class, 'store'])->name('evenements.store');
        Route::get('/evenements', [OrganisateurEvenementController::class, 'index'])->name('evenements.index');
        Route::get('/evenements/{id}/edit', [OrganisateurEvenementController::class, 'edit'])->name('evenements.edit');
        Route::put('/evenements/{id}', [OrganisateurEvenementController::class, 'update'])->name('evenements.update');
        Route::delete('/evenements/{id}', [OrganisateurEvenementController::class, 'destroy'])->name('evenements.destroy');
    });

    // --- Routes de RESERVATION et PAIEMENT QUI NÉCESSITENT L'AUTHENTIFICATION ---
    // La soumission de la réservation (POST) et toutes les routes de paiement
    // devraient être derrière l'authentification.
    Route::post('/reservation', [ReservationController::class, 'store'])
        ->name('reservation.store');


        // Routes pour les actions sur les billets d'une réservation
    Route::post('/reservation/{reservation}/send-tickets', [ReservationController::class, 'sendTickets'])
        ->name('reservation.send-tickets');
    Route::get('/reservation/{reservation}/download-tickets', [ReservationController::class, 'downloadTicketsPdf'])
        ->name('reservation.download-tickets');
        
    // Routes pour le paiement
    Route::get('/payment/{reservation}', [PaymentController::class, 'create'])
        ->name('payment.create');

    Route::get('/payment/success/{reservation}', [PaymentController::class, 'success'])
        ->name('payment.success');

    Route::get('/payment/cancel/{reservation}', [PaymentController::class, 'cancel'])
        ->name('payment.cancel');

    // Route de confirmation
    Route::get('/reservation/{reservation}/confirmation', function($reservation) {
        return Inertia::render('Reservation/Confirmation', [
            'reservation' => $reservation
        ]);
    })->name('reservation.confirmation');
    
});