<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisateur extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $table = 'organisateur'; //because Laravel's Eloquent models (e.g., Organisateur.php) will automatically look for the plural form of the model name in lowercase 
    protected $primaryKey = 'idOrganisateur';

    protected $fillable = [
        'user_id',
        'societe',
        'secteurActivite',
        'ICE',
        'nomGerant',
        'prenomGerant',
        'estActif',
        'estValide',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNomCompletAttribute()
    {
        return $this->prenomGerant . ' ' . $this->nomGerant; // Concatène prenomGerant et nomGerant
    }

    public function evenements()
    {
        return $this->hasMany(Event::class, 'idorganisateur', 'idOrganisateur'); 
    }
}
