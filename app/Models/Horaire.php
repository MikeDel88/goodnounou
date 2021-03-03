<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Horaire
 */

class Horaire extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'horaires';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contrat_id',
        'heure_debut',
        'heure_fin',
        'description',
        'nombre_heures',
        'depose_par',
        'recupere_par',
        'jour_garde',
    ];

    /**
     * Contrat
     *
     * @return void
     */
    public function contrat()
    {
        return $this->belongsTo(Contrat::class, 'contrat_id');
    }
}
