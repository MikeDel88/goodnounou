<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User as User;
use App\Models\Favoris as Favoris;
use App\Models\Contrats as Contrats;
use App\Models\Enfant as Enfant;

class Parents extends Model
{
    use HasFactory;

    protected $table = 'parents';

    /**
     * categorie
     * Relation polymorphe avec l'objet utilisateur
     * @return void
     */
    public function categorie()
    {
        return $this->morphOne(User::class, 'categorie');
    }

    public function enfants()
    {
        return $this->hasMany(Enfant::class, 'parent_id');
    }

    public function favoris()
    {
        return $this->hasMany(Favoris::class, 'parent_id');
    }

    public function contrats()
    {
        return $this->hasMany(Contrats::class, 'parent_id');
    }
}
