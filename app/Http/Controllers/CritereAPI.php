<?php

namespace App\Http\Controllers;

use App\Models\Critere;
use Illuminate\Http\Request;

/**
 * CritereAPI
 */
class CritereAPI extends Controller
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
         * Permet de mettre à jour si l'utilisateur à coché ou non un critère
         */
        $reponse = ($request->value === true) ? true : false;
        Critere::where('assistante_maternelle_id', intval($id))
            ->update([$request->critere => $reponse]);
    }
}
