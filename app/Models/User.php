<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Events\Registered;
use App\Models\AssistanteMaternelle;
use App\Models\Parents;

/**
 * User
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'categorie_type',
        'categorie_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Categorie
     * Relation polymorphe d'un objet utilisateur en deux catégories (parent ou assistante-maternelle)
     *
     * @return void
     */
    public function categorie()
    {
        return $this->morphTo();
    }

    /**
     * AdresseComplete
     *
     * @return void
     */
    public function adresseComplete()
    {
        return "$this->adresse  $this->code_postal  $this->ville";
    }
}
