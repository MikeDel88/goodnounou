<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * PlanningController
 */
class PlanningController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (intval($id) === Auth::user()->id) {
            $this->data['role'] = $this->role();
            $this->data['planning'] = '';
            $this->data['js'][] = 'planning';

            return view('planning', $this->data);
        } else {
            return back()->with('message', "Cette page n'est pas accessible");
        }
    }
}
