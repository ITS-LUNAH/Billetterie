<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organisateur;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class OrganisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $organisateurUser = User::create([
            'typeUser' => 'Organisateur', 
            'firstName' => 'basma',
            'lastName' => 'basma',
            'email' => 'basma@gmail.com',
            'password' => Hash::make('123456789'), // Always hash passwords for security!
        ]);

        Organisateur::create([
            'user_id' => $organisateurUser->id,
            'societe' => 'maroc web',
            'secteurActivite' => 'le web',
            'ICE' => '1111',
            'nomGerant' => 'basma',
            'prenomGerant' => 'basma',
        ]);
    }
}