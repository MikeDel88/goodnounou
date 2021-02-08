<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critere;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RechercheController extends Controller
{
    private array $data = [];

    public function __construct()
    {
        $this->middleware('auth');
        $this->data['role'] = 'parents';
    }

    public function index()
    {
        $schemaCriteres = Schema::getColumnListing('criteres');
        foreach($schemaCriteres as $critere){
            if($critere !== 'id' && $critere !== 'assistante_maternelle_id' && $critere !== 'created_at' && $critere !== 'updated_at'){
                $this->data['criteres'][] = $critere;
            }
        }
        $this->data['js'][] = "geolocalisation";
        
        return view('recherche', $this->data);
    }


}
