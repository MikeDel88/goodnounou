<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;

class AccueilController extends Controller
{
    private array $data = [];

    /**
     * __construct
     * Chargement des fichiers assets pour l'affichage de la page accueil
     * @return void
     */
    public function __construct()
    {
        $this->data['js'] = ['app-mobile', 'app', 'app-animation'];
        $this->data['title'] = 'accueil';
        $this->data['css'] = ['layout', 'accueil'];
    }

    /**
     * index
     * Ajout de la metadescription pour le référencement et chargement de la vue
     * @return void
     */
    public function index()
    {
        $this->data['metadescription'] = "Site de recherche d'assistantes maternelles par géolocalisation, gestion et suivi personnalisé des enfants à travers un planning et un carnet de bord";
        return view('accueil', $this->data);
    }
}
