<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enfant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class EnfantController extends Controller
{

    private array $data = [];
    
    /**
     * __construct
     * Utilise le middleware auth pour récupérer les informations de l'utilisateur
     * @return void
     */
    public function __construct(){

        $this->middleware('auth');
        $this->data['role'] = 'parents';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['enfants'] = Enfant::where('parent_id', Auth::user()->categorie->id)->get();
        return view('liste_enfants', $this->data);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->input(), [
            'nom'               => 'string|bail|required',
            'prenom'            => 'string|bail|required',
            'date_naissance'    => 'date_format:"Y-m-d"|before_or_equal:today',
        ])->validate();

        Enfant::create([
            'parent_id'       =>  Auth::user()->categorie->id,
            'nom'             =>  ucFirst($request->input('nom')),
            'prenom'          =>  ucFirst($request->input('prenom')),
            'date_naissance'  =>  $request->input('date_naissance'),
        ]);

        return back()->with('success', "Votre enfant a bien été crée");
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $enfant = Enfant::findOrFail(intval($id)); // Récupère l'enfant dont l'id a été demandé par l'utilisateur

        /**
         * Vérifie si le parent de l'enfant est bien l'utilisateur connecté
         */
        if($enfant->parent_id === Auth::user()->categorie->id){
            $this->data['enfant'] = $enfant;
            return view('fiche_enfant', $this->data);
        }else{
            return back()->with('error403', "Désolé cette enfant n'est pas accessible");
        }
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
        Validator::make($request->input(), [
            'nom'               => 'string|bail|required',
            'prenom'            => 'string|bail|required',
            'date_naissance'    => 'date_format:"Y-m-d"|before_or_equal:today',
        ])->validate();

        Enfant::where('id', intval($id))
        ->update([
            'nom'             =>  ucFirst($request->input('nom')),
            'prenom'          =>  ucFirst($request->input('prenom')),
            'date_naissance'  =>  $request->input('date_naissance'),
        ]);

        return back()->with('success', "Votre enfant a bien été modifié");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $enfant = Enfant::findOrFail(intval($id));
        
        /**
         * Vérifie si le parent de l'enfant est bien l'utilisateur connecté
         */
        if($enfant->parent_id === Auth::user()->categorie->id){

            Enfant::where('id', $enfant->id)->delete();
            return redirect('/liste/enfants')->with('success', "L'enfant a bien été supprimé");

        }else{

            return redirect('/profile')->with('message', "Cette page n'est pas autorisé");

        }
    }
}
