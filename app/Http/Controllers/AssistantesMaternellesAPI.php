<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssistantesMaternelles;
use Illuminate\Support\Facades\DB;

class AssistantesMaternellesAPI extends Controller
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json([
            'status' => intval($id),
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

        if(isset($request->visible)){
            $value = ($request->visible === true) ? true : false;
            AssistantesMaternelles::where('id', intval($id))
            ->update(['visible' => $value]);  
        }
        if(isset($request->disponible)){
            $value = ($request->disponible === true) ? true : false;
            AssistantesMaternelles::where('id', intval($id))
            ->update(['disponible' => $value]);  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
