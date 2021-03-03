<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * RechercheAPI
 */
class RechercheAPI extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $categorie = 'AssistantesMaternelles';
        $binding = [
            'lat1' => $request->lat,
            'lat2' => $request->lat,
            'lng' => $request->lng,
        ];

        // Requête qui permet de sélectionner un utilisateur en fonction de sa localisation géographique
        $sql = "SELECT assistantes_maternelles.id, users.nom, users.prenom, lat, lng, ( 6371 * acos( cos( radians(:lat1) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(:lng) ) + sin( radians(:lat2) ) * sin( radians( lat ) ) ) ) AS distance
        FROM assistantes_maternelles, users, criteres
        WHERE users.categorie_id = assistantes_maternelles.id
        AND users.categorie_type LIKE '%$categorie'
        AND criteres.assistante_maternelle_id = assistantes_maternelles.id
        AND assistantes_maternelles.visible = 1 ";

        // Si des critères ont été sélectionnés par l'utilisateur, on complète la requête
        if (!empty($request->criteres)) {
            foreach ($request->criteres as $key => $critere) {
                $sql = $sql . " AND criteres.$critere = 1 ";
            }
        }

        $sql = $sql . "HAVING distance < " . $request->distance . " ORDER BY distance";
        $result = DB::select($sql, $binding);

        return response()->json($result);

    }

}
