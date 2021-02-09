<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongTo(Parents::class, 'foreign_key');
    }
}
