<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        //'telphone',
        'email',
        'password',
        'typeUser',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function administrateur()
{
    return $this->hasOne(Administrateur::class);
}

   public function organisateur()
{
    return $this->hasOne(Organisateur::class, 'user_id');
}

    public function participant()
{
    return $this->hasOne(Participant::class);
}

// --- Accesseur pour obtenir le profil spécifique ---
    // Cela permet de faire $user->profile où $user->profile sera soit un Organisateur, Participant, ou null.
    public function getProfileAttribute()
    {
        switch ($this->typeUser) {
            case 'Administrateur':
                return $this->administrateur;
            case 'Organisateur':
                return $this->organisateur;
            case 'Participant':
                return $this->participant;
            default:
                return null;
        }
    }
}
