<?php

namespace App\Http\Controllers;

use App\Models\AssistantesMaternelles;
use Illuminate\Http\Request;

/**
 * AssistantesMaternellesAPI
 */
class AssistantesMaternellesAPI extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Requête
     * @param int                      $id      Id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * Permet de mettre à jour si l'utilisateur
         * veut être visible ou non dans les recherches
         */
        if (isset($request->visible)) {
            $value = ($request->visible === true) ? true : false;
            AssistantesMaternelles::where('id', intval($id))
                ->update(['visible' => $value]);
        }

        /**
         * Permet de mettre à jour si l'utilisateur est disponible
         * pour une garde d'enfant ou non
         */
        if (isset($request->disponible)) {
            $value = ($request->disponible === true) ? true : false;
            AssistantesMaternelles::where('id', intval($id))
                ->update(['disponible' => $value]);
        }
    }
}
