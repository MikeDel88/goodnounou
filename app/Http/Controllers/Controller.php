<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;

/**
 * Controller
 */
class Controller extends BaseController
{
    protected array $messages = [];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->messages = [
            'erreur' => 'Désolé une erreur est survenue',
            'erreur_page' => 'Désolé cette page n\'existe pas',
            'erreur_acces' => 'Désolé mais cette pas n\'est pas accessible',
            'erreur_document' => 'Ce document n\'est pas accessible',
        ];
    }
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
