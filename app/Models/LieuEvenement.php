<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LieuEvenement extends Model
{
    //
    use HasFactory;

    protected $table = 'lieu_evenements'; // Specify table name
    protected $primaryKey = 'idlieu';

    protected $fillable = [
        'nom',
        'ville',
        'adresse',
        'capaciteMax'
    ];

    public function datesEvenements()
    {
        return $this->hasMany(date_evenement::class, 'idlieu', 'idlieu');
    }

}
