<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    use HasFactory;

    protected $table = 'evenements'; // Specify table name
    protected $primaryKey = 'idevenement';

    protected $fillable = [
        'titre',
        'description',
        'lieu',
        'bandeannonce',
        'typeplacement',
        'estvalide',
        'idorganisateur',
        'prixbase',
    ];

    // Relationship to Organisateur
    public function organisateur()
    {
        return $this->belongsTo(Organisateur::class, 'user_id', 'idOrganisateur');
    }
    // Relationship to DateEvenement (an event can have multiple dates/occurrences)
    public function dates()
    {
        return $this->hasMany(DateEvenement::class, 'idevenement', 'idevenement');
    }

    /* // Relationship to Billets (tickets)
    public function billets()
    {
        return $this->hasMany(Billet::class, 'idEvenement', 'idEvenement');
    }

    // Relationship to Promotions
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'idEvenement', 'idEvenement'); // Assuming idPromotion in Promotion references idEvenement implicitly
    } */


}
