<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Messages;
use App\Models\Enfant;
use App\Models\AssistantesMaternelles;
use Illuminate\Support\Facades\Auth;

class MessagesAPI extends Controller
{
    /**
     * Display a listing of the resource.
     * Retourne les messages d'un enfant issu d'une assistantes maternelle
     * @param  int  $idParent
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($idParent, $id)
    {
        $enfant = Enfant::findOrFail(intval($id));
        if($enfant->parents->id === intval($idParent)){
            foreach($enfant->messages->sortByDesc('jour_garde') as $message){
                $assistanteMaternelle =  AssistantesMaternelles::find($message->assistante_maternelle_id);
                
                $infos = new \stdClass();
                $infos->id = $message->id;
                $infos->assistante_maternelle = "{$assistanteMaternelle->categorie->nom} {$assistanteMaternelle->categorie->prenom}";;
                $infos->contenu = $message->contenu;
                $infos->date = $message->jour_garde;
                $data[] = $infos;
            }
            return response()->json([
            'messages' => $data,
            ]); 
        }
    }


    /**
     * Display the specified resource.
     *  Retourne les messages d'un enfant issu d'une assistantes maternelle
     * @param  int  $assMatId
     * @param  int  $enfantId
     * @return \Illuminate\Http\Response
     */
    public function show($assMatId, $enfantId)
    {
        $messages = Messages::where('assistante_maternelle_id', ':assMat')->where('enfant_id', ':enfant')->orderBy('jour_garde', 'desc')->setBindings(['assMat' => intval($assMatId), 'enfant' => $enfantId])->get();
         return response()->json([
            'messages' => $messages,
        ]); 
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