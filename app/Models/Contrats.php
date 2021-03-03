<?php

namespace App\Models;

use App\Models\AssistantesMaternelles as AssistantesMaternelles;
use App\Models\Horaire as Horaire;
use App\Models\Parents as Parents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Contrats
 */

class Contrats extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contrats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date_debut',
        'parent_id',
        'assistante_maternelle_id',
        'enfant_id',
        'nombre_heures',
        'nombre_semaines',
        'taux_horaire',
        'taux_entretien',
        'frais_repas',
    ];

    /**
     * AssistanteMaternelle
     *
     * @return void
     */
    public function assistanteMaternelle()
    {
        return $this->belongsTo(AssistantesMaternelles::class, 'assistante_maternelle_id');
    }

    /**
     * Parent
     *
     * @return void
     */
    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    /**
     * Enfant
     *
     * @return void
     */
    public function enfant()
    {
        return $this->belongsTo(Enfant::class, 'enfant_id');
    }

    /**
     * Status
     *
     * @return void
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * Horaire
     *
     * @return void
     */
    public function horaire()
    {
        return $this->hasMany(Horaire::class, 'contrat_id');
    }
}
