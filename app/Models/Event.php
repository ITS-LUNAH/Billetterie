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
        'categorie',
        'estvalide',
        'idorganisateur',
        'prixbase',
        'estActif',
    ];
    protected $casts = [
        'estvalide' => 'boolean',
        'prixbase' => 'float',
    ];
    
    // Relationship to Organisateur
    public function organisateur()
    {
        return $this->belongsTo(Organisateur::class, 'idOrganisateur', 'idOrganisateur');
    }
    // Relationship to DateEvenement (an event can have multiple dates/occurrences)
    public function dates()
    {
        return $this->hasMany(DateEvenement::class, 'idevenement', 'idevenement');
    }

    // Relationship to Billets (tickets)
    public function billets()
    {
        return $this->hasMany(Billet::class, 'idEvenement', 'idEvenement');
    }

    // Relationship to Promotions
    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'idEvenement', 'idEvenement'); // Assuming idPromotion in Promotion references idEvenement implicitly
    }

    public function lieu()
    {
        return $this->belongsTo(Lieu::class, 'idlieu', 'idlieu'); // Assurez idlieu est correct
    }

    // Scope pour la recherche
    public function scopeSearch($query, $search, $location, $date)
    {
        return $query->when($search, function ($query, $search) {
                return $query->where('titre', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($location, function ($query, $location) {
                return $query->where('lieu', 'like', "%{$location}%")
                           ->orWhereHas('dates.lieu', function ($q) use ($location) {
                               $q->where('ville', 'like', "%{$location}%")
                                 ->orWhere('nom', 'like', "%{$location}%");
                           });
            })
            ->when($date, function ($query, $date) {
                return $query->whereHas('dates', function ($q) use ($date) {
                    $q->whereDate('datedebut', $date);
                });
            })
            ->where('estvalide', true);
    }

    // Accesseur pour obtenir la prochaine date
    public function getProchaineDateAttribute()
    {
        return $this->dates()
                   ->where('datedebut', '>=', now())
                   ->orderBy('datedebut')
                   ->first();
    }

}
