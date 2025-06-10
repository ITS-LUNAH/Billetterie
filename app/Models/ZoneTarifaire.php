<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneTarifaire extends Model
{
    //
    use HasFactory;
    protected $table = 'zone_tarifaire'; // Nom de la table associée
    protected $primaryKey = 'idzone'; // Clé primaire de la table
    protected $fillable = [
        'nomzone',
        'tarif',
        'capacite',
        'couleur',
        'idlieu',
    ];

    // Relation avec les Billets (une zone peut avoir plusieurs billets associés)
    public function billets()
    {
        return $this->hasMany(Billet::class, 'idzone', 'idzone');
    }

    // Relation avec le Lieu (si la zone est spécifique à un lieu)
    public function lieu()
    {
        return $this->belongsTo(Lieu::class, 'idlieu', 'idlieu');
    }

}
