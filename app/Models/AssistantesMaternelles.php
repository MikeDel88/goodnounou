<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User as User;

class AssistantesMaternelles extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

    protected $table = 'assistantes_maternelles';

    public function categorie()
    {
        return $this->morphToMany(User::class, 'categorie');
    }
}
