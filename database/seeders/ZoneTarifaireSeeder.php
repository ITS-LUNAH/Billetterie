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
            'capacite' => 100, // Exemple: 100 places dans la zone standard
            'couleur' => '#90EE90', // Vert clair
            'idlieu' => $lieuRabat->idlieu, // Lier à un lieu si vous avez la colonne
        ]);

        ZoneTarifaire::create([
            'nomzone' => 'VIP',
            'tarif' => 400.00,
            'capacite' => 20, // Exemple: 20 places dans la zone VIP
            'couleur' => '#ADD8E6', // Bleu clair
            'idlieu' => $lieuRabat->idlieu,
        ]);

        ZoneTarifaire::create([
            'nomzone' => 'Premium',
            'tarif' => 250.00,
            'capacite' => 50, // Exemple: 50 places dans la zone premium
            'couleur' => '#FFD700', // Or
            'idlieu' => $lieuRabat->idlieu,
        ]);

        // Si vous avez d'autres lieux, créez des zones pour eux ou rendez les zones génériques
    }

    
}
