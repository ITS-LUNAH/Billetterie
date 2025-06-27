<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ParticipantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur est un participant
        $user = Auth::user();
        
        // Adapter selon votre structure de base de données
        // Option 1: Si vous avez un champ user_type dans la table users
        if ($user->typeUser !== 'Participant') {
            abort(403, 'Accès refusé. Vous devez être un participant.');
        }

        // Option 2: Si vous vérifiez via la relation avec la table participants
        // if (!$user->participant) {
        //     abort(403, 'Accès refusé. Vous devez être un participant.');
        // }

        return $next($request);
    }
}
