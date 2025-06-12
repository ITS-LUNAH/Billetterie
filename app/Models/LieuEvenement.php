<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LieuEvenement extends Model
{
    //
    use HasFactory;

    protected $table = 'lieu_evenements'; // Specify table name
    protected $primaryKey = 'idlieu';

    protected $fillable = [
        'nom',
        'ville',
        'adresse',
        'capaciteMax'
    ];
    protected $casts = [
        'capaciteMax' => 'integer'
    ];

    public function datesEvenements()
    {
        return $this->hasMany(datesEvenements::class, 'idlieu', 'idlieu');
    }

    // Accesseur pour l'adresse complète
    public function getAdresseCompleteAttribute()
    {
        return $this->adresse . ', ' . $this->ville;
    }
    
}
