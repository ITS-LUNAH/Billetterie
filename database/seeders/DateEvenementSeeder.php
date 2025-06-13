<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use App\Models\Evenement;
use App\Models\LieuEvenement;

class DateEvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $evenementTechMaroc = Event::where('titre', 'Conférence Tech Maroc')->first();
        $lieuLeGrandThéâtre = LieuEvenement::where('nom', 'Le Grand Théâtre')->first();

        DB::table('date_evenements')->insert([
            [
                'datedebut' => '2025-09-10 09:00:00',
                'heuredebut' => '09:00:00',
                'heureouvertureportes' => '08:30:00',
                'placesdisponibles' => 800, // Total = 3000 (capaciteMax du lieu 2)
                'placesvendues' => 120,
                'idevenement' => $evenementTechMaroc->idevenement, // << CORRIGEZ EN idEvenement
                'idlieu' => $lieuLeGrandThéâtre->idlieu,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'datedebut' => '2025-07-20 18:00:00',
                'heuredebut' => '18:00:00',
                'heureouvertureportes' => '17:00:00',
                'placesvendues' => 80,
                'placesdisponibles' => 200, // Total = 10000 (capaciteMax du lieu 3)
                'idevenement' => 2, // Festival Gnaoua Vibes
                'idlieu' => 3,      // Stade Al Amal (Casablanca)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'datedebut' => '2025-07-21 18:00:00',
                'heuredebut' => '18:00:00',
                'heureouvertureportes' => '17:00:00',
                'placesvendues' => 500,
                'placesdisponibles' => 500, // Total = 10000 (capaciteMax du lieu 3)
                'idevenement' => 2, // Festival Gnaoua Vibes (deuxième jour)
                'idlieu' => 3,      // Stade Al Amal (Casablanca)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'datedebut' => '2025-10-05 20:00:00',
                'heuredebut' => '20:00:00',
                'heureouvertureportes' => '19:30:00',
                'placesvendues' => 100,
                'placesdisponibles' => 400, // Total = 500 (capaciteMax du lieu 4)
                'idevenement' => 3, // Pièce de Théâtre : L'Envol
                'idlieu' => 4,      // Centre Culturel (Fès)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'datedebut' => '2025-10-06 20:00:00',
                'heuredebut' => '20:00:00',
                'heureouvertureportes' => '19:30:00',
                'placesvendues' => 80,
                'placesdisponibles' => 420, // Total = 500 (capaciteMax du lieu 4)
                'idevenement' => 3, // Pièce de Théâtre : L'Envol (deuxième jour)
                'idlieu' => 4,      // Centre Culturel (Fès)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
