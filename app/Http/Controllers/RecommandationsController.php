<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recommandations;
use Illuminate\Support\Facades\Validator;

class RecommandationsController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(intval($request->parent) === Auth::user()->categorie->id){
            Validator::make($request->input(), [
                'parent'                            => 'integer|bail|required',
                'assistante-maternelle'             => 'integer|bail|required',
                'avis'                              => 'string|bail|required',
            ])->validate();

        try{
            Recommandations::updateOrCreate(
                ['parent_id' => Auth::user()->categorie->id, 'assistante_maternelle_id' => $request->input('assistante-maternelle')],
                ['avis' => $request->input('avis')]
            );
            return back()->with('success', "Votre Avis a bien été enregistré");

        }catch(\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
               return back()->with('message', 'Cet enfant existe déjà');
            }
        }
        }else{
            return back()->with('message', "Désolé cette action n'est pas possible");
        }
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(intval($request->parent) === Auth::user()->categorie->id){
            Recommandations::where('parent_id', $request->input('parent'))->where('assistante_maternelle_id', $request->input('assistante-maternelle'))->delete();
            return back()->with('success', 'Votre avis et votre note ont bien été supprimé');
        }else{
            return back()->with('message', "Désolé cette action n'est pas possible");
        }
    }
}
