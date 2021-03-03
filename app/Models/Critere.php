<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssistantesMaternelles as AssistantesMaternelles;

class Critere extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'criteres';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assistante_maternelle_id'
    ];

    public function assistanteMaternelle()
    {
        return $this->belongTo(AssistantesMaternelles::class, 'assistante_maternelle_id', 'id');
    }
}
