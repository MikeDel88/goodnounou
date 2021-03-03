<?php

namespace App\Http\Controllers;

use App\Models\User;

/**
 * PlanningAPI
 */
class PlanningAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = User::findOrFail(intval($id));
        $contrats = $user->categorie->contrats;

        foreach ($contrats as $contrat) {
            if ($contrat->status_id === 2) {
                $horaires[] = [
                    'enfant' => $contrat->enfant->prenom,
                    'horaires' => $contrat->horaire,
                ];
            }
        }

        return response()->json(['events' => $horaires]);
    }
}
