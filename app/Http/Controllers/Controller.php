<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * role
     * Permet de connaitre la catégorie à laquelle appartient l'utisateur en cours
     * @return void
     */
    protected function role()
    {
        // Vérifie si la catégorie à laquelle appartient l'utilisateur connecté
        return (get_class(Auth::user()->categorie) === 'App\Models\Parents') ? 'parents' : 'assistante-maternelle';
    }
}
