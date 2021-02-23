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
use Illuminate\Database\QueryException;


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
            try{
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

            }catch(\Illuminate\Database\QueryException $e){
                
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    return back()->with('message', 'Un contrat avec cette assistante maternelle et cet enfant existe déjà');
                }
            }
    
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
        $this->data['role'] = $this->role();

        if($this->data['role'] === 'parents' && Auth::user()->categorie->id === $contrat->parent_id){

            $this->data['js'][] = 'horaires';
            $this->data['contrat'] = $contrat;
            $this->data['mois'] = [
                1   => 'Janvier',
                2   => 'Février',
                3   => 'Mars',
                4   => 'Avril',
                5   => 'Mai',
                6   => 'Juin',
                7   => 'Juillet',
                8   => 'Août',
                9   => 'Septembre',
                10  => 'Octobre',
                11  => 'Novembre',
                12  => 'Décembre'
            ];
            $this->data['salaire_mensuel'] = round((($contrat->taux_horaire * $contrat->nombre_heures * $contrat->nombre_semaines) / 12), 2);
            $this->data['nombre_heures_lisse'] = ceil((($contrat->nombre_heures * $contrat->nombre_semaines) / 12));
            return view('fiche_contrat_parents', $this->data);

       }else{
           return back()->with('message', "Désolé mais ce contrat n'est pas disponible");
       }
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
        if(Auth::user()->categorie->id === $contrat->parent_id && $contrat->status_id === 3 || Auth::user()->categorie->id === $contrat->parent_id && $contrat->status_id === 1){
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
        if(Auth::user()->categorie->id === $contrat->assistante_maternelle_id && $contrat->status_id === 1){
            $this->update($id, 2);
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
        if(Auth::user()->categorie->id === $contrat->assistante_maternelle_id && $contrat->status_id === 1){
            $this->update($id, 3);
            return back()->with('success', 'Le contrat a bien été refusé');
        }else{
            return back()->with('message', "Désolé mais ce contrat n'existe pas");
        }
    }
    
    /**
     * clos
     *  Cloture un contrat de la part d'un parent
     * @param  mixed $id
     * @return void
     */
    public function clos($id)
    {
        // Recherche si le numéro de contrat existe dans la liste des contrats de l'utilisateur connecté
        foreach(Auth::user()->categorie->contrats as $contrat){
            $status = ($contrat->id === intval($id)) ? true : false;
        }
        // En fonction du retour, on clos le contrat ou bien on redirige avec un message d'erreur
        if($status === true){
            $this->update($id, 4);
            return back()->with('success', 'Le contrat a bien été clôturé');
        }else{
            return back()->with('message', "Désolé, cette page cette opération n'est pas autorisé !");
        }
    }
    
    /**
     * update
     *  Met à jour le status d'un contrat
     * @param  mixed $id
     * @param  mixed $status
     * @return void
     */
    public function update($id, $status)
    {
        Contrat::where('id', $id)->update([
            'status_id' => $status
        ]);
    }
}
