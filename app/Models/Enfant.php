<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Parents as Parents;
use App\Models\Contrats as Contrats;
use App\Models\Messages as Messages;

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
     * Parents
     * Relation un enfant appartient à un parent
     *
     * @return void
     */
    public function parents()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    /**
     * Contrats
     *
     * @return void
     */
    public function contrats()
    {
        return $this->hasMany(Contrats::class, 'enfant_id');
    }

    /**
     * Messages
     *
     * @return void
     */
    public function messages()
    {
        return $this->hasMany(Messages::class, 'enfant_id');
    }
}
