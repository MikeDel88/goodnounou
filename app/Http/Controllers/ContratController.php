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
        
        $this->data['role'] = $this->role(); // Détermine s'il s'agit d'un parent ou d'une nounou qui est connecté

        if($this->data['role'] === 'parents'){
            $this->data['liste_favoris'] = Parents::find(Auth::user()->categorie_id)->favoris; // Récupère des favoris pour l'utilisateur connecté
            $this->data['enfants'] = Parents::find(Auth::user()->categorie_id)->enfants; // Récupère la liste des enfants de l'utilisateur connecté
        }

        $this->data['contrats'] = ($this->data['role'] === 'parents') ? Parents::find(Auth::user()->categorie_id)->contrats : AssistantesMaternelles::find(Auth::user()->categorie_id)->contrats; // Récupère la liste des contrats pour l'utilisateur connecté
        
        return view('contrats', $this->data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $liste_enfants = Parents::findOrFail(Auth::user()->categorie_id)->enfants; // Récupère la liste des enfants de l'utilisateur connecté
        $liste_favoris = Parents::findOrFail(Auth::user()->categorie_id)->favoris; // Récupère la liste des favoris de l'utilisateur connecté
        
        // Pour chaque enfant de la liste, on vérifie si l'id correspond à la valeur de l'enfant renseigné
        foreach($liste_enfants as $enfant){
            $checkEnfant = ($enfant->id === intval($request->input('enfant'))) ? true : false;
        }
        foreach($liste_favoris as $favoris){
            $checkFavoris = ($favoris->assistante_maternelle_id === intval($request->input('assistante_maternelle'))) ? true : false;
        }

        if(isset($checkEnfant) && $checkEnfant === true && isset($checkFavoris) && $checkFavoris === true){

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


            $assistanteMaternelle = AssistantesMaternelles::findOrFail($request->input('assistante_maternelle')); // Récupère l'assistante maternelle concerné au contrat pour enregistrer ses frais afin de les conserver si toutefois l'assistante maternelle change ses taux sur son profil
           
            /**
             * Création du contrat
             */
            Contrat::create([
                'date_debut'                    => $request->input('date_debut'),
                'enfant_id'                     => $request->input('enfant'),
                'assistante_maternelle_id'      => $assistanteMaternelle->id,
                'parent_id'                     => Auth::user()->categorie->id,
                'nombre_heures'                 => $request->input('nombre_heures'),
                'nombre_semaines'               => $request->input('nombre_semaines'),
                'taux_horaire'                  => $assistanteMaternelle->taux_horaire,
                'taux_entretien'                => $assistanteMaternelle->taux_entretien,
                'frais_repas'                   => $assistanteMaternelle->frais_repas,
            ]);

            return back()->with('success', 'Contrat enregistré');

        }else{
            return redirect('/contrats')->with('message', "Désolé, une erreur est survenue");
        }
            
    }

    /**
     * Display the specified resource.
     * Fiche du contrat pour l'assistante maternelle
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contrat = Contrat::findOrFail(intval($id));
        $this->data['role'] = $this->role();

        if($this->data['role'] === 'assistante-maternelle' && Auth::user()->categorie->id === $contrat->assistante_maternelle_id){
            
            $this->data['contrat'] = $contrat;
            $this->data['salaire_mensuel'] = round((($contrat->taux_horaire * $contrat->nombre_heures * $contrat->nombre_semaines) / 12), 2); // Calcul du salaire mensuel estimé 
            $this->data['nombre_heures_mois'] = ceil((($contrat->nombre_heures * $contrat->nombre_semaines) / 12));
            return view('fiche_contrat_ass_mat', $this->data);

       }else{
           return back()->with('message', "Désolé mais ce contrat n'est pas disponible");
       }
    }

    /**
     * Show the form for editing the specified resource.
     * Fiche du contrat pour les parents qui peuvent supprimer le contrat
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $contrat = Contrat::findOrFail(intval($id));
        if($this->role() === 'parents' && Auth::user()->categorie->id === $contrat->parent_id){
            echo "parent ok";
       }else{
           return back()->with('message', "Désolé mais ce contrat n'est pas disponible");
       }
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
        $contrat = Contrat::findOrFail(intval($id));
        if(Auth::user()->categorie->id === $contrat->parent_id && $contrat->status === 'Refus' || Auth::user()->categorie->id === $contrat->parent_id && $contrat->status === 'En attente'){
            Contrat::where('id', $id)->delete();
            return back()->with('success', 'Le contrat a bien été supprimé');
        }else{
            return back()->with('message', "Désolé mais ce contrat n'existe pas");
        }
    }

    
    /**
     * validation
     * Validation d'un contrat par une assistante maternelle connecté
     * @param  mixed $id
     * @return void
     */
    public function validation($id)
    {
        $contrat = Contrat::findOrFail(intval($id));
        if(Auth::user()->categorie->id === $contrat->assistante_maternelle_id && $contrat->status === 'En attente'){
            Contrat::where('id', $id)->update([
                'status' => 'En cours'
            ]);
            return back()->with('success', 'Le contrat a bien été validé');
        }else{
            return back()->with('message', "Désolé mais ce contrat n'existe pas");
        }
    }
    
    /**
     * refus
     *  Refus d'un contrat par une assistante maternelle connecté
     * @param  mixed $id
     * @return void
     */
    public function refus($id)
    {
        $contrat = Contrat::findOrFail(intval($id));
        if(Auth::user()->categorie->id === $contrat->assistante_maternelle_id && $contrat->status === 'En attente'){
            Contrat::where('id', $id)->update([
                'status' => 'Refus'
            ]);
            return back()->with('success', 'Le contrat a bien été refusé');
        }else{
            return back()->with('message', "Désolé mais ce contrat n'existe pas");
        }
    }
}
