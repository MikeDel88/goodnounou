<?php

namespace App\Http\Controllers;

use App\Models\Contrats;
use App\Models\Horaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * HorairesController
 */
class HorairesController extends Controller
{

    private array $_messages = [];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_messages = ['doublon' => 'Un horaire existe déjà pour ce jour'];
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

        // Vérifie d'abord sur l'utilisateur connecté est bien un parent
        if ($this->role() === 'parents') {
            // Récupère la liste des contrats de l'utilisateur parent connecté
            $listeContrats = Contrats::where('parent_id', Auth::user()->categorie->id)->get();
            $listeIdContrats = [];

            foreach ($listeContrats as $contrat) {
                // Met dans un tableau tous les id des contrats appartenant à l'utilisateur parent connecté
                $listeIdContrats[] = $contrat->id;

                // Permettra de vérifier si la date renseigné est postérieur ou égal à la date du contrat
                if (intval($request->input('contrat_id')) === $contrat->id) {
                    $date_debut = $contrat->date_debut;
                }
            }

            /**
             * Validation des données
             */
            Validator::make(
                $request->input(), [
                'contrat_id' => ['required', Rule::in($listeIdContrats)],
                'jour_garde' => "after_or_equal:$date_debut|required",
                'nombre_heures' => 'string|bail|required',
                'debut_contrat' => "date_format:$date_debut|required",
                'heure_debut' => 'date_format:H:i',
                'depose_par' => 'string|nullable',
                'heure_fin' => 'date_format:H:i|after:heure_debut',
                'recupere_par' => 'string|nullable',
                'description' => 'string|nullable',
                ]
            )->validate();

            /**
             * Création de l'horaire
             */
            try {
                Horaire::create(
                    [
                    'contrat_id' => $request->input('contrat_id'),
                    'nombre_heures' => $request->input('nombre_heures'),
                    'jour_garde' => $request->input('jour_garde'),
                    'heure_debut' => $request->input('heure_debut'),
                    'depose_par' => $request->input('depose_par'),
                    'heure_fin' => $request->input('heure_fin'),
                    'recupere_par' => $request->input('recupere_par'),
                    'description' => $request->input('description'),
                    ]
                );

                $dateinit = \Carbon\Carbon::parse($request->input('jour_garde'));
                return back()->with('success', "L'horaire pour le {$dateinit->format('d/m/Y')} a bien été enregistré");
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {
                    return back()->with('message', $this->_messages['doublon']);
                }
            }
        } else {
            return back()->with('message', $this->messages['erreur_acces']);
        }
    }
}
