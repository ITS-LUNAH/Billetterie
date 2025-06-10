<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    //
    use HasFactory;
    
    protected $table = 'billets'; // Nom de la table associée
    protected $primaryKey = 'idbillet'; // Clé primaire de la table

    protected $fillable = [
        'idevenement',
        'estscanne',
        'dateValidation',
        'QRcode',
        'idparticipant',
        'idreservation',
        'idzone',
    ];

    // Relation avec l'événement
    public function evenement()
    {
        return $this->belongsTo(Event::class, 'idevenement', 'idevenement');
    }

    // Relation avec le participant
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'idparticipant', 'idparticipant');
    }

    // Relation avec la réservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }

    // Relation avec la ZoneTarifaire
    public function zoneTarifaire()
    {
        return $this->belongsTo(ZoneTarifaire::class, 'idzone', 'idzone');
    }

}
