<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Events\Registered;
use App\Models\AssistanteMaternelle;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Parents;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;




/**
 * User
 */
class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasFactory;
    use Notifiable;
    use InteractsWithMedia;



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
     * RegisterMediaCollections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {

        $this
            ->addMediaCollection("avatar-$this->id")
            ->singleFile();
    }

    /**
     * RegisterMediaConversions
     *
     * @param mixed $media Media Conversion
     *
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->nonOptimized()
            ->sharpen(10);
    }




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
