<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Parents;
use App\Models\AssistantesMaternelles;
use App\Models\User;
use App\Models\Enfant as Enfant;
use App\Models\Contrats as Contrat;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;


class ContratController extends Controller
{

    /**
     * __construct
     * Utilise le middleware auth pour récupérer les informations de l'utilisateur
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * Faire apparaître la liste des nounous en favoris si role = parent
         * Faire le lien vers la fiche du contrat
         * Faire la bulle dans le menu avec le nombre de contrat en cours
         */
        $this->data['role'] = $this->role(); // Détermine s'il s'agit d'un parent ou d'une nounou qui est connecté

        if($this->data['role'] === 'parents'){
            $this->data['liste_favoris'] = Parents::find(Auth::user()->categorie_id)->favoris; // Récupère des favoris pour l'utilisateur connecté
            $this->data['enfants'] = Parents::find(Auth::user()->categorie_id)->enfants; // Récupère la liste des enfants de l'utilisateur connecté
        }

        $this->data['contrats'] = ($this->data['role'] === 'parents') ? Parents::find(Auth::user()->categorie_id)->contrats : AssistantesMaternelles::find(Auth::user()->categorie_id)->contrats; // Récupère la liste des contrats pour l'utilisateur connecté
        
        return view('contrats', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $liste_enfants = Parents::find(Auth::user()->categorie_id)->enfants; // Récupère la liste des enfants de l'utilisateur connecté
        $liste_favoris = Parents::find(Auth::user()->categorie_id)->favoris; // Récupère la liste des favoris de l'utilisateur connecté
        
        // Pour chaque enfant de la liste, on vérifie si l'id correspond à la valeur de l'enfant renseigné
        foreach($liste_enfants as $enfant){
            $checkEnfant = ($enfant->id === intval($request->input('enfant'))) ? true : false;
        }
        foreach($liste_favoris as $favoris){
            $checkFavoris = ($favoris->assistante_maternelle_id === intval($request->input('assistante_maternelle'))) ? true : false;
        }

        if($checkEnfant === true && $checkFavoris === true){

            /**
             * Validation des données
             */
            Validator::make($request->input(), [
                'date_debut'                    => 'date_format:"Y-m-d"|required',
                'enfant'                        => 'required|integer',
                'assistante_maternelle'         => 'required|integer',
                'nombre_heures'                 => 'required|integer|min:0|max:48',
                'nombre_semaines'               => 'required|integer|min:0|max:48',
            ])->validate();

            /**
             * Création du contrat
             */
            Contrat::create([
                'date_debut'                    => $request->input('date_debut'),
                'enfant_id'                     => $request->input('enfant'),
                'assistante_maternelle_id'      => $request->input('assistante_maternelle'),
                'parent_id'                     => Auth::user()->categorie->id,
                'nombre_heures'                 => $request->input('nombre_heures'),
                'nombre_semaines'               => $request->input('nombre_semaines'),
            ]);

            return back()->with('success', 'Contrat enregistré');

        }else{
            return redirect('/contrats')->with('message', "Désolé, une erreur est survenue");
        }
            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
