<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 

class EvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         DB::table('evenements')->insert([
            [
                'idevenement' => 1,
                'titre' => 'Conférence Tech Maroc',
                'description' => 'Conférence annuelle sur les nouvelles technologies, l\'innovation et la transformation digitale au Maroc.',
                'lieu' => 'Palais des Congrès', // Descriptif
                'bandeannonce' => 'image1.jpg',
                'typeplacement' => 'Libre',
                'estvalide' => true,
                'idorganisateur' => 1,
                'prixbase' => 500.00,
                'categorie' => 'Conférence', // Ajout de la catégorie
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idevenement' => 2,
                'titre' => 'Festival',
                'description' => 'Festival international.',
                'lieu' => 'Scène Plein Air', // Descriptif
                'bandeannonce' => 'image2.jpg',
                'typeplacement' => 'Libre',
                'estvalide' => true,
                'idorganisateur' => 1, 
                'prixbase' => 200.00,
                'categorie' => 'Festival', // Ajout de la catégorie
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idevenement' => 3,
                'titre' => 'Pièce de Théâtre : L\'Envol',
                'description' => 'Drame contemporain explorant des thèmes différents.',
                'lieu' => 'Théâtre municipal', // Descriptif
                'bandeannonce' => 'image3.jpg',
                'typeplacement' => 'Numéroté',
                'estvalide' => true,
                'idorganisateur' => 1,
                'prixbase' => 150.00,
                'categorie' => 'Théâtre', // Ajout de la catégorie
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idevenement' => 4,
                'titre' => 'Salon de l\'Emploi et de la Formation',
                'description' => 'Forum de recrutement et d\'orientation pour les jeunes diplômés et les professionnels en quête d\'opportunités.',
                'lieu' => 'Espace d\'Exposition', // Descriptif
                'bandeannonce' => 'image4.jpg',
                'typeplacement' => 'Libre',
                'estvalide' => false, // Cet événement n'est pas encore validé
                'idorganisateur' => 1,
                'prixbase' => 100.00, 
                'categorie' => 'Emploi', // Ajout de la catégorie
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
