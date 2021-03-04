<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * PlanningController
 */
class PlanningController extends Controller
{

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id Id
     *
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
            return back()->with('message', $this->messages['erreur_acces']);
        }
    }
}
