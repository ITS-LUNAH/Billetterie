<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Organisateur;
use App\Models\Participant;
use App\Models\Administrateur;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\DB; // Pour les transactions


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Règles de validation communes
        $rules = [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'typeUser' => ['required', 'string', 'in:Administrateur,Organisateur,Participant'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',        ];

        // Ajout des règles de validation spécifiques au type d'utilisateur
        if ($input['typeUser'] === 'Organisateur') {
            $rules['nomGerant'] = ['required', 'string', 'max:255'];
            $rules['prenomGerant'] = ['required', 'string', 'max:255'];
            $rules['societe'] = ['required', 'string', 'max:255'];
            $rules['secteurActivite'] = ['required', 'string', 'max:255'];
            $rules['ICE'] = ['required', 'string', 'max:255', 'unique:organisateur']; // Unique dans la table organisateur
        } elseif ($input['typeUser'] === 'Participant') {
            $rules['compteBancaire'] = ['required', 'string', 'max:255'];
            $rules['IBAN'] = ['required', 'string', 'max:255', 'unique:participant']; // Unique dans la table participant
            $rules['RIB'] = ['required', 'string', 'max:255', 'unique:participant']; // Unique dans la table participant
        }

        // Pas de règles spécifiques pour Administrateur si aucun champ spécifique

        Validator::make($input, $rules)->validate();

        // Utilisation d'une transaction pour s'assurer que si la création du profil échoue, l'utilisateur n'est pas créé non plus.
        return DB::transaction(function () use ($input) {
            // Création de l'utilisateur de base
            $user = User::create([
                'firstName' => $input['firstName'],
                'lastName' => $input['lastName'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'typeUser' => $input['typeUser'],
            ]);

            // Création du profil spécifique en fonction du type d'utilisateur
            switch ($input['typeUser']) {
                case 'Administrateur':
                    Administrateur::create([
                        'user_id' => $user->id,
                    ]);
                    break;
                case 'Organisateur':
                    Organisateur::create([
                        'user_id' => $user->id,
                        'nomGerant' => $input['nomGerant'],
                        'prenomGerant' => $input['prenomGerant'],
                        'societe' => $input['societe'],
                        'secteurActivite' => $input['secteurActivite'],
                        'ICE' => $input['ICE'],
                        'estActif' => false, // Par défaut, l'organisateur est inactif
                    ]);
                    break;
                case 'Participant':
                    Participant::create([
                        'user_id' => $user->id,
                        'compteBancaire' => $input['compteBancaire'],
                        'IBAN' => $input['IBAN'],
                        'RIB' => $input['RIB'],
                    ]);
                    break;
            }

            return $user;
        });
    }
}