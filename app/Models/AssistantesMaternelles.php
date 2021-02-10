<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User as User;
use App\Models\Critere as Critere;
use App\Models\Favoris as Favoris;


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
        return $this->morphToMany(User::class, 'categorie');
    }

    public function criteres()
    { 
        return $this->belongsTo(Critere::class); 
    }

    public function favoris()
    { 
        return $this->belongsToMany(Favoris::class); 
    }

}
