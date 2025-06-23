<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    //
    use HasFactory;
    
    protected $table = 'billets'; 
    protected $primaryKey = 'idbillet';
    
    protected $fillable = [
        'idevenement',
        'iddate',
        'estscanne',
        'dateValidation',
        'QRcode',
        'idparticipant',
        'idreservation',
        'idzone',
    ];
    protected $casts = [
        'estscanne' => 'boolean',
        'datevalidation' => 'datetime'
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
    public function dateEvenement()
    {
        return $this->belongsTo(DateEvenement::class, 'iddate', 'iddate');
    }

    // Relation avec la ZoneTarifaire
    public function zone()
    {
        return $this->belongsTo(ZoneTarifaire::class, 'idzone', 'idzone');
    }

    // Générer un QR code unique
    public static function generateQRCode()
    {
        return 'EVUP-' . uniqid() . '-' . time();
    }

}
