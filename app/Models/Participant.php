<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Participant extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $table = 'participant'; //because Laravel's Eloquent models (e.g., Participant.php) will automatically look for the plural form of the model name in lowercase


    protected $primaryKey = 'idParticipant';

    protected $fillable = [
        'compteBancaire',
        'IBAN',
        'RIB',
        'user_id',
        'nom',
        'prenom',
        'telephone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    // Relation avec les réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idParticipant', 'idParticipant');
    }
    // Relation avec les billets
    public function billets()
    {
        return $this->hasMany(Billet::class, 'idparticipant', 'idparticipant');
    }
}
