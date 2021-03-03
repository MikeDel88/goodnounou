<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

/**
 * Controller
 */
class Controller extends BaseController
{

    /**
     * Role
     * Permet de connaitre la catégorie à laquelle appartient l'utisateur en cours
     *
     * @return void
     */
    protected function role()
    {
        // Vérifie si la catégorie à laquelle appartient l'utilisateur connecté
        return (get_class(Auth::user()->categorie) === 'App\Models\Parents') ? 'parents' : 'assistante-maternelle';
    }
}
