<?php

/**
 * Controller
 */

namespace App\Http\Controllers;

use App\Models\AssistantesMaternelles;
use App\Models\Contrats as Contrat;
use App\Models\Parents;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * ContratController
 */
class ContratController extends Controller
{
    private array $_messages;
    /**
     * __construct
     * Utilise le middleware auth pour récupérer les informations de l'utilisateur
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->_messages = [
            'enregistre' => 'Contrat enregistré',
            'validation' => 'Le contrat a bien été validé',
            'refus' => 'Le contrat a bien été refusé',
            'suppression' => 'Le contrat a bien été supprimé',
            'clos' => 'Le contrat a bien été clôturé',
            'doublon' => 'Un contrat avec cette assistante maternelle et cet enfant existe déjà',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->data['role'] = $this->role();

        if ($this->data['role'] === 'parents') {
            $this->data['liste_favoris'] = Parents::find(Auth::user()->categorie_id)->favoris;
            $this->data['enfants'] = Parents::find(Auth::user()->categorie_id)->enfants;
        }

        $this->data['contrats'] = ($this->data['role'] === 'parents') ? Parents::find(Auth::user()->categorie_id)->contrats : AssistantesMaternelles::find(Auth::user()->categorie_id)->contrats;
        return view('contrats', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request Requête
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $liste_enfants = Parents::findOrFail(Auth::user()->categorie_id)->enfants;
        $liste_favoris = Parents::findOrFail(Auth::user()->categorie_id)->favoris;

        foreach ($liste_enfants as $enfant) {
            $checkEnfant = (intval($enfant->id) === intval($request->input('enfant'))) ? true : false;
            if ($checkEnfant) {
                break;
            }
        }
        foreach ($liste_favoris as $favoris) {
            $checkFavoris = (intval($favoris->assistante_maternelle_id) === intval($request->input('assistante_maternelle'))) ? true : false;
            if ($checkFavoris) {
                break;
            }
        }

        if (isset($checkEnfant) && $checkEnfant === true && isset($checkFavoris) && $checkFavoris === true) {

            /**
             * Validation des données
             */
            Validator::make(
                $request->input(),
                [
                    'date_debut' => 'date_format:"Y-m-d"|required',
                    'enfant' => 'required|integer',
                    'assistante_maternelle' => 'required|integer',
                    'nombre_heures' => 'required|integer|min:0|max:48',
                    'nombre_semaines' => 'required|integer|min:0|max:48',
                ]
            )->validate();

            $assistanteMaternelle = AssistantesMaternelles::findOrFail($request->input('assistante_maternelle'));

            /**
             * Création du contrat
             */
            try {
                Contrat::create(
                    [
                        'date_debut' => $request->input('date_debut'),
                        'enfant_id' => $request->input('enfant'),
                        'assistante_maternelle_id' => $assistanteMaternelle->id,
                        'parent_id' => Auth::user()->categorie->id,
                        'nombre_heures' => $request->input('nombre_heures'),
                        'nombre_semaines' => $request->input('nombre_semaines'),
                        'taux_horaire' => $assistanteMaternelle->taux_horaire,
                        'taux_entretien' => $assistanteMaternelle->taux_entretien,
                        'frais_repas' => $assistanteMaternelle->frais_repas,
                    ]
                );
                return back()->with('success', $this->_messages['enregistre']);
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {
                    return back()
                        ->with('message', $this->_messages['doublon']);
                }
            }
        } else {
            return redirect('/contrats')
                ->with('message', $this->messages['erreur']);
        }
    }

    /**
     * Display the specified resource.
     * Fiche du contrat pour l'assistante maternelle
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contrat = Contrat::findOrFail(intval($id));
        $this->data['role'] = $this->role();

        if ($this->data['role'] === 'assistante-maternelle' && Auth::user()->categorie->id === intval($contrat->assistante_maternelle_id)) {
            $this->data['contrat'] = $contrat;
            $this->data['salaire_mensuel'] = round((($contrat->taux_horaire * $contrat->nombre_heures * $contrat->nombre_semaines) / 12), 2);
            $this->data['nombre_heures_mois'] = ceil((($contrat->nombre_heures * $contrat->nombre_semaines) / 12));
            return view('fiche_contrat_ass_mat', $this->data);
        } else {
            return back()->with('message', $this->messages['erreur_acces']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * Fiche du contrat pour les parents qui peuvent supprimer le contrat
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contrat = Contrat::findOrFail(intval($id));
        $this->data['role'] = $this->role();

        if ($this->data['role'] === 'parents' && Auth::user()->categorie->id === intval($contrat->parent_id)) {
            $this->data['js'][] = 'horaires';
            $this->data['contrat'] = $contrat;
            $this->data['mois'] = [
                1 => 'Janvier',
                2 => 'Février',
                3 => 'Mars',
                4 => 'Avril',
                5 => 'Mai',
                6 => 'Juin',
                7 => 'Juillet',
                8 => 'Août',
                9 => 'Septembre',
                10 => 'Octobre',
                11 => 'Novembre',
                12 => 'Décembre',
            ];
            $this->data['salaire_mensuel'] = round((($contrat->taux_horaire * $contrat->nombre_heures * $contrat->nombre_semaines) / 12), 2);
            $this->data['nombre_heures_lisse'] = ceil((($contrat->nombre_heures * $contrat->nombre_semaines) / 12));
            return view('fiche_contrat_parents', $this->data);
        } else {
            return back()
                ->with('message', $this->messages['erreur_acces']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contrat = Contrat::findOrFail(intval($id));
        if (intval(Auth::user()->categorie->id) === intval($contrat->parent_id) && intval($contrat->status_id) === 3 || intval(Auth::user()->categorie->id) === intval($contrat->parent_id) && intval($contrat->status_id) === 1) {
            Contrat::where('id', $id)->delete();
            return back()->with('success', $this->_messages['suppression']);
        } else {
            return back()->with('message', $this->messages['erreur_page']);
        }
    }

    /**
     * Validation
     * Validation d'un contrat par une assistante maternelle connecté
     *
     * @param int $id Id
     *
     * @return void
     */
    public function validation($id)
    {
        $contrat = Contrat::findOrFail(intval($id));
        if (intval(Auth::user()->categorie->id) === intval($contrat->assistante_maternelle_id) && intval($contrat->status_id) === 1) {
            $this->update($id, 2);
            return back()
                ->with('success', $this->_messages['validation']);
        } else {
            return back()
                ->with('message', $this->messages['erreur']);
        }
    }

    /**
     * Refus
     * Refus d'un contrat par une assistante maternelle connecté
     *
     * @param int $id Id
     *
     * @return void
     */
    public function refus($id)
    {
        $contrat = Contrat::findOrFail(intval($id));
        if (Auth::user()->categorie->id === $contrat->assistante_maternelle_id && $contrat->status_id === 1) {
            $this->update($id, 3);
            return back()
                ->with('success', $this->_messages['refus']);
        } else {
            return back()
                ->with('message', $this->messages['erreur']);
        }
    }

    /**
     * Clos
     * Cloture un contrat de la part d'un parent
     *
     * @param mixed $id Id
     *
     * @return void
     */
    public function clos($id)
    {

        foreach (Auth::user()->categorie->contrats as $contrat) {
            $status = (intval($contrat->id) === intval($id)) ? true : false;
            if ($status === true) {
                break;
            }
        }

        if ($status === true) {
            $this->update($id, 4);
            return back()
                ->with('success', $this->_messages['clos']);
        } else {
            return back()
                ->with('message', $this->messages['erreur']);
        }
    }

    /**
     * UPDATE
     * MAJ DU CONTRAT
     *
     * @param mixed $id     Id
     * @param mixed $status Status
     *
     * @return void
     */
    public function update($id, $status)
    {
        Contrat::where('id', $id)->update(['status_id' => $status]);
    }
}
