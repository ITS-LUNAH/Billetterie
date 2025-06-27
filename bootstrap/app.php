<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\OrganisateurMiddleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
        $middleware->alias([
            'admin' => AdminMiddleware::class, 
            'organisateur' => OrganisateurMiddleware::class,
            'participant' => \App\Http\Middleware\ParticipantMiddleware::class,

        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    // C'est la section pour le planificateur de tâches
    ->withSchedule(function (Schedule $schedule) { // <-- MODIFICATION ICI
        // Planifier notre commande de nettoyage :
        // Elle sera vérifiée toutes les minutes par 'schedule:work'.
        // Elle agira si des réservations de plus de 10 minutes sont "en attente".
        $schedule->command('reservations:clean-unpaid')->everyMinute();
        // ^^^ 'everyMinute()' est idéal pour les tests en développement.
    }) // <-- FIN DE LA SECTION DU PLANIFICATEUR
    ->create();
