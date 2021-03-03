<?php

namespace App\Http\Controllers;

use App\Models\Recommandations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * RecommandationsController
 *
 * @category Controller
 */
class RecommandationsController extends Controller
{
    /**
     * Affiche la liste des recommandations
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->role() === 'assistante-maternelle') {
            echo "ok";
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
            Validator::make($request->input(), [
                'parent' => 'integer|bail|required',
                'assistante-maternelle' => 'integer|bail|required',
                'avis' => 'string|bail|required',
            ])->validate();

            try {
                $assMat = 'assistante-maternelle';
                Recommandations::updateOrCreate(
                    [
                        'parent_id' => Auth::user()->categorie->id,
                        "assistante_maternelle_id" => $request->input($assMat),
                    ],
                    ['avis' => $request->input('avis')]
                );
                return back()->with('success', "Votre avis a bien été enregistré");
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {
                    return back()->with('message', 'Cet enfant existe déjà');
                }
            }
        } else {
            return back()->with('message', "Désolé cette action n'est pas possible");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param request $request Requête
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (intval($request->parent) === Auth::user()->categorie->id) {
            Recommandations::where('parent_id', $request->input('parent'))
                ->where('assistante_maternelle_id', $request->input('assistante-maternelle'))
                ->delete();
            return back()->with('success', 'Votre avis et votre note ont bien été supprimé');
        } else {
            return back()->with('message', "Désolé cette action n'est pas possible");
        }
    }
}
