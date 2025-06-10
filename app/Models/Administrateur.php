<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Administrateur extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $table = 'administrateur'; //because Laravel's Eloquent models (e.g., Administrateur.php) will automatically look for the plural form of the model name in lowercase 

    
    protected $primaryKey = 'idAdmin';

    protected $fillable = ['
    user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
