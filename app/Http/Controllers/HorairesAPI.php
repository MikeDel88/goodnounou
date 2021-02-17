<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horaire;
use Illuminate\Support\Facades\DB;

class HorairesAPI extends Controller
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
    public function show($contrat, $mois, $annee)
    {
       $listeContrats = Horaire::where('contrat_id', ':contrat')->whereMonth('jour_garde', ':mois')->whereYear('jour_garde', ':annee')->orderBy('jour_garde', 'asc')->setBindings(['contrat' => intval($contrat), 'mois' => $mois, 'annee' => $annee])->get();

       return response()->json([
            'horaire' => $listeContrats,
            ]); 
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
