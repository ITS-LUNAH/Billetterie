<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;


class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
         $user = Auth::user();

        // Vérifiez si l'utilisateur existe et a la propriété 'typeuser'
    if ($user && property_exists($user, 'typeUser')) {
        $userType = $user->typeUser; 
        switch ($userType) {
            case 'Organisateur':
                return '/resources/js/pages/Organisateur/Dashboard.vue';
            case 'Participant':
                return '/resources/js/pages/Dashboard.vue';
            case 'Administrateur':
                return '/resources/js/pages/Admin/Dashboard.vue';
            default:
                return '/home'; // Redirection par défaut si le usertype n'est pas géré
        }
    }
    }
}
