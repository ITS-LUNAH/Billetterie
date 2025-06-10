<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    //
     use HasFactory;

    protected $table = 'reservations'; // Nom de la table associée
    protected $primaryKey = 'idreservation'; // Clé primaire de la table
    
    protected $fillable = [
        'datereservation',
        'etat',
        'idparticipant',
    ];

    //Relation avec le participant
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'idparticipant', 'idparticipant');
    }

    //Relation avec les billets (une réservation peut avoir plusieurs billets)
     public function billets()
    {
        return $this->hasMany(Billet::class, 'idReservation', 'idReservation');
    }

}
