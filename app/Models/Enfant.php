<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Models\Parents as Parents;
use App\Models\Contrats as Contrats;
use App\Models\Messages as Messages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Enfant
 */
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
     * getAge
     *
     * @return void
     */
    public function getAge()
    {
        $age = Carbon::parse($this->date_naissance)->age;
        if ($age < 1) {
            // return date("m", strtotime($this->date_naissance)) - date('m') . " mois";
            $dateJour = date_create("now");
            $dateAnnivaire = date_create($this->date_naissance);
            $interval = date_diff($dateAnnivaire, $dateJour, true);
            return $interval->format('%m mois');

        } else if ($age === 1) {
            return $age . " an";
        } else {
            return $age . " ans";
        }

    }

    /**
     * GetIdentite
     *
     * @return void
     */
    public function getIdentite()
    {
        return "$this->nom $this->prenom";
    }

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
