<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ZoneTarifaire;
use App\Models\LieuEvenement;

class ZoneTarifaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        
        // Récupérer un lieu existant
        $lieuRabat = LieuEvenement::where('ville', 'Rabat')->first();
        if (!$lieuRabat) {
            throw new \Exception('Lieu "Rabat" non trouvé. Assurez-vous qu’il existe dans la base de données.');
        }
        // Créer des zones tarifaires d'exemple
        ZoneTarifaire::create([
            'nomzone' => 'Standard',
            'tarif' => 150.00,
            'capacite' => 100, 
            'couleur' => '#90EE90', 
            'idlieu' => $lieuRabat->idlieu,
        ]);

        ZoneTarifaire::create([
            'nomzone' => 'VIP',
            'tarif' => 400.00,
            'capacite' => 20, 
            'couleur' => '#ADD8E6', 
            'idlieu' => $lieuRabat->idlieu,
        ]);

        ZoneTarifaire::create([
            'nomzone' => 'Premium',
            'tarif' => 250.00,
            'capacite' => 50,
            'couleur' => '#FFD700', 
            'idlieu' => $lieuRabat->idlieu,
        ]);

    }

    
}
