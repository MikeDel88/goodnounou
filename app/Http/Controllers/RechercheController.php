<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critere;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * RechercheController
 */

class RechercheController extends Controller
{
    private array $data = [];

/**
 * __construct
 *
 * @return void
 */

    public function __construct()
    {
        $this->middleware('auth');
        $this->data['role'] = 'parents';
    }

    public function index()
    {
        $schemaCriteres = Schema::getColumnListing('criteres'); // Sélection de l'ensemble des champs de la table critère

        // Pour chaque champs, on ajoute les champs qui pourront être sélectionnés dans un tableau spécifique
        foreach($schemaCriteres as $critere){
            if($critere !== 'id' && $critere !== 'assistante_maternelle_id' && $critere !== 'created_at' && $critere !== 'updated_at'){
                $this->data['criteres'][] = $critere;
            }
        }
        $this->data['geolocalisation']  = '';
        $this->data['js'][] = "geolocalisation"; // Ajout d'un fichier spécifique pour la géolocalisation

        return view('recherche', $this->data);
    }


}
