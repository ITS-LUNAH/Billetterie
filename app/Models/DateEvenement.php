<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateEvenement extends Model
{
    //
    use HasFactory;

    protected $table = 'date_evenements'; // Nom de la table associée
    protected $primaryKey = 'iddate';

    protected $fillable = [
        'datedebut',
        'heuredebut',
        'heureouvertureportes',
        'placesvendues',
        'placesdisponibles',
        'idevenement',
        'idlieu'
    ];

    protected $casts = [
        'datedebut' => 'datetime', 
    ];
    
    // Relationship to Event
    public function evenement()
    {
        return $this->belongsTo(Event::class, 'idevenement', 'idevenement');
    }
    
    // Relationship to LieuEvenement
    public function lieu()
    {
        return $this->belongsTo(LieuEvenement::class, 'idlieu', 'idlieu');
    }

}
