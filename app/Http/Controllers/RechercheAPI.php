<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechercheAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // Requête qui permet de sélectionner un utilisateur en fonction de sa localisation géographique
        $sql = "SELECT assistantes_maternelles.id, users.nom, users.prenom, lat, lng, ( 6371 * acos( cos( radians(".$request->lat.") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(".$request->lng.") ) + sin( radians(".$request->lat.") ) * sin( radians( lat ) ) ) ) AS distance 
        FROM assistantes_maternelles, users, criteres 
        WHERE users.categorie_id = assistantes_maternelles.id 
        AND criteres.assistante_maternelle_id = assistantes_maternelles.id AND assistantes_maternelles.visible = 1 ";

        // Si des critères ont été sélectionné par l'utilisateur, on complète la requête
        if(!empty($request->criteres)){
            foreach($request->criteres as $key => $critere){
                $sql = $sql . " AND criteres.$critere = 1 ";
            }
        }
           
        $sql = $sql . "HAVING distance < ".$request->distance." ORDER BY distance";
        $result = DB::select($sql, ['lat' => $request->lat, 'lng' => $request->lng]);

        return response()->json($result);
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
