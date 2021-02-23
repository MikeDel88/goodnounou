<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssistantesMaternelles;
use App\Models\Enfant;

class Messages extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assistante_maternelle_id',
        'enfant_id',
        'contenu',
        'jour_garde'
    ];

    public function assistanteMaternelle()
    { 
        return $this->belongsTo(AssistantesMaternelles::class, 'assistante_maternelle_id'); 
    }

    public function enfant()
    { 
        return $this->belongsTo(Enfant::class, 'enfant_id'); 
    }
}
