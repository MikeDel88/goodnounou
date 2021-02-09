<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User as User;

class Parents extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];
    
    protected $table = 'parents';
    
    /**
     * categorie
     * Relation polymorphe avec l'objet utilisateur
     * @return void
     */
    public function categorie()
    {
        return $this->morphToMany(User::class, 'categorie');
    }
}
