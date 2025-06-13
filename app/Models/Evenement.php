<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Evenement extends Model
{
    use HasFactory;

    protected $table = 'evenements';
    protected $primaryKey = 'idevenement';

    protected $casts = [
        'estValide' => 'boolean',
    ];

    protected $fillable = [
        'titre',
        'description',
        'lieu',
        'bandeannonce',
        'typeplacement',
        'estvalide',
        'idOrganisateur',
        'prixbase',
    ];

    // Ajout de debug dans les événements Eloquent
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            Log::info('Création d\'un événement en cours...', $model->toArray());
        });
        
        static::created(function ($model) {
            Log::info('Événement créé avec succès!', ['id' => $model->idevenement]);
        });
        
        static::saving(function ($model) {
            Log::info('Sauvegarde d\'un événement...', $model->toArray());
        });
    }

    // Relationship to Organisateur
    public function organisateur()
    {
        return $this->belongsTo(Organisateur::class, 'idOrganisateur', 'idOrganisateur');
    }

    // Relationship to DateEvenement
    public function dates()
    {
        return $this->hasMany(DateEvenement::class, 'idevenement', 'idevenement');
    }

    // Relationship to Billets
    public function billets()
    {
        return $this->hasMany(Billet::class, 'idevenement', 'idevenement');
    }

}