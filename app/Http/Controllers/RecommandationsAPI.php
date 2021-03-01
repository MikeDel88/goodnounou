<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recommandations;

class RecommandationsAPI extends Controller
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
        if($request->note < 6 && $request->note > 0 || $request->note === null){
            Recommandations::updateOrCreate(
                ['parent_id' => $request->parent, 'assistante_maternelle_id' => $request->nounou],
                ['note' => $request->note]
            );
            $status = true;
        }else{
            $status = false;
        }
        return response()->json([
                'status' => $status
        ]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
