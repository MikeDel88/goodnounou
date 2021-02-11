<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Parents as Parents;
use App\Models\Contrats as Contrats;

class Enfant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enfants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'parent_id'
    ];

    
    /**
     * parents
     * Relation un enfant appartient à un parent
     * @return void
     */
    public function parents()
    {
        return $this->belongTo(Parents::class, 'parent_id');
    }

    public function contrats()
    { 
        return $this->hasMany(Contrats::class, 'enfant_id'); 
    }
}
