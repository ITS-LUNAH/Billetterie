<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class LieuEvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('lieu_evenements')->insert([
            [
                'idlieu' => 1,
                'nom' => 'Le Grand Théâtre',
                'ville' => 'Rabat',
                'adresse' => 'Avenue Mohammed V, Quartier Hassan',
                'capaciteMax' => 1500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idlieu' => 2,
                'nom' => 'Palais des Congrès',
                'ville' => 'Marrakech',
                'adresse' => 'Avenue Prince Moulay Abdellah',
                'capaciteMax' => 3000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idlieu' => 3,
                'nom' => 'Stade Al Amal',
                'ville' => 'Casablanca',
                'adresse' => 'Boulevard des Sports',
                'capaciteMax' => 10000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idlieu' => 4,
                'nom' => 'Centre Culturel',
                'ville' => 'Fès',
                'adresse' => 'Rue Talaa Kebira',
                'capaciteMax' => 500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
