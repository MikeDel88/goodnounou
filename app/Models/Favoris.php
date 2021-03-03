<?php

namespace App\Models;

use App\Models\AssistantesMaternelles as AssistantesMaternelles;
use App\Models\Parents as Parents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Favoris
 */

class Favoris extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'favoris';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'assistante_maternelle_id',
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
     * Parents
     *
     * @return void
     */
    public function parents()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }
}
