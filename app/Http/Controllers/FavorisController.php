<?php

namespace App\Http\Controllers;

use App\Models\Favoris;

/**
 * FavorisController
 */
class FavorisController extends Controller
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
     * Show
     * Permet à un parent de consulter la liste de ses assistantes maternelles favorites
     * et d'accéder à leurs fiches
     *
     * @return void
     */
    public function show()
    {
        $this->data['role'] = $this->role();

        if ($this->data['role'] === 'parents') {
            return view('favoris', $this->data);
        } else {
            return back()
                ->with('message', $this->messages['erreur_acces']);
        }
    }
}
