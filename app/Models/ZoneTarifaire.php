<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneTarifaire extends Model
{
    //
    use HasFactory;

    protected $table = 'zone_tarifaire'; 
    protected $primaryKey = 'idzone'; 
    
    protected $fillable = [
        'nomzone',
        'tarif',
        'capacite',
        'couleur',
        'idlieu',
    ];
    protected $casts = [
        'tarif' => 'float',
        'capacite' => 'integer'
    ];

    // Relation avec le Lieu (si la zone est spécifique à un lieu)
    public function lieu()
    {
        return $this->belongsTo(LieuEvenement::class, 'idlieu', 'idlieu');
    }
    // Relation avec les Billets (une zone peut avoir plusieurs billets associés)
    public function billets()
    {
        return $this->hasMany(Billet::class, 'idzone', 'idzone');
    }

    // Méthode pour obtenir les places disponibles
    public function getPlacesDisponiblesAttribute()
    {
        $billetsVendus = $this->billets()->count();
        return $this->capacite - $billetsVendus;
    }

}
