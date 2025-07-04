<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use App\Models\Event;
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
                'placesdisponibles' => 800, 
                'placesvendues' => 120,
                'idevenement' => $evenementTechMaroc->idevenement, 
                'idlieu' => $lieuLeGrandThéâtre->idlieu,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'datedebut' => '2025-08-20 08:00:00',
                'heuredebut' => '08:00:00',
                'heureouvertureportes' => '07:30:00',
                'placesdisponibles' => 800, 
                'placesvendues' => 120,
                'idevenement' => $evenementTechMaroc->idevenement,
                'idlieu' => $lieuLeGrandThéâtre->idlieu,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'datedebut' => '2025-07-20 18:00:00',
                'heuredebut' => '18:00:00',
                'heureouvertureportes' => '17:00:00',
                'placesvendues' => 80,
                'placesdisponibles' => 200, 
                'idevenement' => 2, 
                'idlieu' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'datedebut' => '2025-07-21 18:00:00',
                'heuredebut' => '18:00:00',
                'heureouvertureportes' => '17:00:00',
                'placesvendues' => 500,
                'placesdisponibles' => 500, 
                'idevenement' => 2,
                'idlieu' => 3, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'datedebut' => '2025-10-05 20:00:00',
                'heuredebut' => '20:00:00',
                'heureouvertureportes' => '19:30:00',
                'placesvendues' => 100,
                'placesdisponibles' => 400,
                'idevenement' => 3, 
                'idlieu' => 4, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'datedebut' => '2025-10-06 20:00:00',
                'heuredebut' => '20:00:00',
                'heureouvertureportes' => '19:30:00',
                'placesvendues' => 80,
                'placesdisponibles' => 420,
                'idevenement' => 3,
                'idlieu' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
