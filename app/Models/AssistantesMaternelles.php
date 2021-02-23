<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User as User;
use App\Models\Critere as Critere;
use App\Models\Favoris as Favoris;
use App\Models\Contrats as Contrats;
use App\Models\Messages as Messages;


class AssistantesMaternelles extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visible'
    ];

    protected $table = 'assistantes_maternelles';
    
    /**
     * categorie
     * Relation polymorphe avec l'objet utilisateur
     * @return void
     */
    public function categorie()
    {
        return $this->morphOne(User::class, 'categorie');
    }

    public function criteres()
    { 
        return $this->hasOne(Critere::class, 'assistante_maternelle_id', 'id'); 
    }

    public function favoris()
    { 
        return $this->hasMany(Favoris::class, 'assistante_maternelle_id'); 
    }

    public function contrats()
    { 
        return $this->hasMany(Contrats::class, 'assistante_maternelle_id'); 
    }

    public function messages()
    { 
        return $this->hasMany(Messages::class, 'assistante_maternelle_id'); 
    }

}
