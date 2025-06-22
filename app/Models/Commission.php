<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $primaryKey = 'idcommission';
    protected $fillable = ['idevenement', 'taux'];

    public function evenement()
    {
        return $this->belongsTo(Event::class, 'idevenement', 'idevenement');
    }
}
