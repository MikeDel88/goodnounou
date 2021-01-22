<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    /**
     * user
     * Une catégorie peut avoir plusieurs utilisateurs
     * @return void
     */
    public function user() 
    { 
        return $this->hasMany(User::class); 
    }
}
