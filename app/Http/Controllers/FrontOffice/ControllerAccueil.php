<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;

class ControllerAccueil extends Controller
{
    private array $data = [];

    /**
     * __construct
     *
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
     *
     * @return void
     */
    public function index()
    {
        $this->data['metadescription'] = "Site de recherche d'assistantes maternelles par géolocalisation, gestion et suivi personnalisé des enfants à travers un planning et un carnet de bord";

        return view('accueil', $this->data);
    }
}
