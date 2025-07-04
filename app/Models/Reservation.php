<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    //
     use HasFactory;

    protected $table = 'reservations'; 
    protected $primaryKey = 'idreservation'; 
    
    protected $fillable = [
        'datereservation',
        'etat',
        'idparticipant',
    ];
    protected $casts = [
        'datereservation' => 'datetime'
    ];

    //Relation avec le participant
    public function participant()
    {
        return $this->belongsTo(Participant::class, 'idparticipant', 'idParticipant');
    }

    //Relation avec les billets (une réservation peut avoir plusieurs billets)
     public function billets()
    {
        return $this->hasMany(Billet::class, 'idreservation', 'idreservation');
    }

    public function evenement()
    {
        return $this->belongsTo(Event::class, 'idevenement', 'idevenement');
    }

    // Méthode pour obtenir le montant total
    public function getMontantTotalAttribute()
    {
        return $this->billets->sum(function ($billet) {
            return $billet->zone ? $billet->zone->tarif : 0;
        });
    }

    // Méthode pour obtenir l'événement (via le premier billet)
    public function getEvenementAttribute()
    {
        return $this->billets->first() ? $this->billets->first()->evenement : null;
    }

    public function getevenement()
    {
         return $this->belongsTo(Event::class, 'idevenement', 'idevenement');
    }


}
