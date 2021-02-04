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
            if($request->visible === true){
                AssistantesMaternelles::where('id', intval($id))
                ->update(['visible' => true]);  
            }elseif($request->visible === false){
                AssistantesMaternelles::where('id', intval($id))
                ->update(['visible' => false]); 
            }
        }
        if(isset($request->disponible)){
            if($request->disponible === true){
                AssistantesMaternelles::where('id', intval($id))
                ->update(['disponible' => true]);  
            }elseif($request->disponible === false){
                AssistantesMaternelles::where('id', intval($id))
                ->update(['disponible' => false]); 
            }
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
