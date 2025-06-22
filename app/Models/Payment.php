<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

        // Les colonnes que vous autorisez à être remplies massivement (Mass Assignment)
        protected $fillable = [
            'idreservation',
            'stripe_payment_intent_id',
            'amount',
            'currency',
            'status',
            'payment_method_type',
            'card_brand',
            'last_four_digits',
            'receipt_url',
        ];

        // Définir la relation avec la réservation
    public function reservation()
    {
            // Un paiement appartient à une réservation
            // 'reservation_id' est la clé étrangère dans la table 'payments'
            // 'idreservation' est la clé primaire dans la table 'reservations'
            return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }
}
    