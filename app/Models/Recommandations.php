<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Recommandations
 */

class Recommandations extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = 'recommandations';

    /**
     * AssistanteMaternelle
     *
     * @return void
     */
    public function assistanteMaternelle()
    {
        return $this->belongsTo(AssistantesMaternelles::class, 'assistante_maternelle_id');
    }
}
