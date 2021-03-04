<?php

namespace App\Http\Controllers;

use App\Models\Recommandations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


/**
 * RecommandationsController
 *
 * @category Controller
 */
class RecommandationsController extends Controller
{

    private array $_data = [];
    private array $_messages = [];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_messages = [
            'validation' => 'Votre avis a bien été enregistré',
            'suppression' => 'Votre avis et votre note ont bien été supprimé'
        ];
    }

    /**
     * Affiche la liste des recommandations
     *
     * @param \Illuminate\Http\Request $request Requête
     *
     * @return \Illuminate\Http\Response
     */
    public function index(? Request $request)
    {

        if ($this->role() === 'assistante-maternelle') {
            $this->_data['role'] = $this->role();
            $this->_data['noteMax'] = 5;
            $this->_data['nombreNote'] = Recommandations::where('assistante_maternelle_id', Auth::user()->categorie_id)
                ->whereNotNull('note')
                ->count();
            $this->_data['nombreAvis'] = Recommandations::where('assistante_maternelle_id', Auth::user()->categorie_id)
                ->whereNotNull('avis')
                ->count();
            $this->_data['moyenne'] = Recommandations::where('assistante_maternelle_id', Auth::user()->categorie_id)
                ->whereNotNull('note')
                ->avg('note');

            if ($request->input('filtre')) {

                Validator::make(
                    $request->input(),
                    ['filtre' => ['required',Rule::in(['note_asc', 'note_desc', 'avis_asc', 'avis_desc'])]]
                );

                $filtre = explode('_', $request->input('filtre'));

                $filtre[0] = ($filtre[0] === 'avis') ? 'recommandations.updated_at' : 'recommandations.note';

                $this->_data['listeAvis'] = Recommandations::join('users', 'users.categorie_id', '=', 'parent_id')
                    ->select('users.nom', 'users.prenom', 'recommandations.updated_at', 'recommandations.note', 'recommandations.avis')
                    ->where('users.categorie_type', 'App\\Models\\Parents')
                    ->where('assistante_maternelle_id', Auth::user()->categorie_id)
                    ->whereNotNull($filtre[0])
                    ->orderBy($filtre[0], $filtre[1])
                    ->paginate(5);

            } else {

                $this->_data['listeAvis'] = Recommandations::join('users', 'users.categorie_id', '=', 'parent_id')
                    ->select('users.nom', 'users.prenom', 'recommandations.updated_at', 'recommandations.note', 'recommandations.avis')
                    ->where('users.categorie_type', 'App\\Models\\Parents')
                    ->where('assistante_maternelle_id', Auth::user()->categorie_id)
                    ->whereNotNull('avis')
                    ->orderByDesc('recommandations.updated_at')
                    ->paginate(5);

            }

            $this->_data['js'][] = 'avis';
            return view('recommandations', $this->_data);

        } else {
            return back()->with('message', $this->messages['erreur_acces']);
        }
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
        if (intval($request->parent) === Auth::user()->categorie->id) {
            Validator::make(
                $request->input(),
                [
                'parent' => 'integer|bail|required',
                'assistante-maternelle' => 'integer|bail|required',
                'avis' => 'string|bail|required',
                ]
            )->validate();

            try {
                $assMat = 'assistante-maternelle';
                Recommandations::updateOrCreate(
                    [
                        'parent_id' => Auth::user()->categorie->id,
                        "assistante_maternelle_id" => $request->input($assMat),
                    ],
                    ['avis' => $request->input('avis')]
                );
                return back()->with('success', $this->_messages['validation']);
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {
                    return back()->with('message', $this->messages['erreur_acces']);
                }
            }
        } else {
            return back()->with('message', $this->messages['erreur']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request Requête
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (intval($request->parent) === Auth::user()->categorie->id) {
            Recommandations::where('parent_id', $request->input('parent'))
                ->where('assistante_maternelle_id', $request->input('assistante-maternelle'))
                ->delete();
            return back()->with('success', $this->_messages['suppression']);
        } else {
            return back()->with('message', $this->messages['erreur']);
        }
    }
}
