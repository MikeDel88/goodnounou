<?php

namespace App\Http\Controllers;

use App\Models\Horaire;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

/**
 * HorairesAPI
 */
class HorairesAPI extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param int $contrat Contrat
     * @param int $mois    Mois
     * @param int $annee   Annee
     *
     * @return \Illuminate\Http\Response
     */
    public function show($contrat, $mois, $annee)
    {
        $listeContrats = Horaire::where('contrat_id', ':contrat')
            ->whereMonth('jour_garde', ':mois')
            ->whereYear('jour_garde', ':annee')
            ->orderBy('jour_garde', 'asc')
            ->setBindings(['contrat' => intval($contrat), 'mois' => $mois, 'annee' => $annee])
            ->get();

        return response()->json(['horaire' => $listeContrats]);
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
        try {
            Horaire::where('contrat_id', ':contrat')
                ->where('id', ':id')
                ->setBindings(['contrat' => intval($request->contrat), 'id' => intval($request->horaire)])
                ->delete();
            $status = 'ok';
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'ko';
        }

        return response()->json(['status' => $status]);
    }
}
