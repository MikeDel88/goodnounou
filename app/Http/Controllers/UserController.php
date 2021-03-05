<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Enfant;
use App\Models\Contrats;
use Illuminate\Support\Collection;
use App\Models\AssistantesMaternelles;
use App\Models\Parents;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Messages;
use Illuminate\Support\Str;
use Image;

/**
 * UserController
 */
class UserController extends Controller
{

    private array $_data = [];

    /**
     * __construct
     * Utilise le middleware auth pour récupérer les informations de l'utilisateur
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * Récupère la liste des enfants de l'utilisateur connecté
         */
        $this->_data['role']     = $this->role();
        $this->_data['enfants']  = ($this->_data['role'] === 'parents') ? Enfant::where('parent_id', Auth::user()->categorie->id)->get() : '';
        $this->_data['contrats'] = ($this->_data['role'] === 'parents') ? Contrats::where('parent_id', Auth::user()->categorie->id)->where('status_id', 2)->get() : Contrats::where('assistante_maternelle_id', Auth::user()->categorie->id)->where('status_id', 2)->get();

        if ($this->_data['role'] === 'assistante-maternelle') {
            $this->_data['messages'] =  Messages::where('assistante_maternelle_id', Auth::user()->categorie->id)->orderByDesc('jour_garde')
                ->limit(5)
                ->get();
        } elseif ($this->_data['role'] === 'parents') {
            foreach (Auth::user()->categorie->enfants as $enfant) {
                $this->_data['messages'] = Messages::where('enfant_id', $enfant->id)
                    ->orderByDesc('jour_garde')
                    ->limit(1)
                    ->get();
            }
        }

        $this->_data['title'] = 'Profile utilisateur';
        return view('profil', $this->_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $user Id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        /**
         * Vérifie si l'utilisateur demandé est bien celui connecté
         */
        if (intval($user) === Auth::user()->id) {
            $this->_data['role'] = $this->role();

            return view('profil_edit', $this->_data);
        }

        return redirect('/profile')->with('message', $this->messages['erreur_acces']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Requête
     * @param int                      $id      Id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * Vérifie si l'utilisateur demandé est bien celui connecté
         */
        if (intval($id) === Auth::user()->id) {
            Validator::make(
                $request->input(),
                [
                'nom'               => 'bail|required',
                'prenom'            => 'bail|required',
                'date_naissance'    => 'date_format:"Y-m-d"|before_or_equal:today',
                'adresse'           => 'required',
                'ville'             => 'required',
                'code_postal'       => 'min:5|max:5',
                'telephone'         => 'nullable|max:10',
                'email_contact'     => 'nullable|email'
                ]
            )->validate();



            /**
             * Si l'utilisateur a renseigné une photo, alors on stocke l'image dans un dossier images et un dossier avec le numéro de l'utilisateur
             */
            if ($request->file('photo') !== null) {
                $request->validate(['photo' => 'image|mimes:jpeg,png,jpg|max:800']);
                $user = User::findOrFail($id);
                $user->addMediaFromRequest('photo')
                    ->usingFileName("avatar-$id.jpg")
                    ->toMediaCollection("avatar-$id");

            }

            User::where('id', $id)
                ->update(
                    [
                    'nom'             =>  ucFirst($request->input('nom')),
                    'prenom'          =>  ucFirst($request->input('prenom')),
                    'date_naissance'  =>  $request->input('date_naissance'),
                    'adresse'         => $request->input('adresse'),
                    'ville'           => ucFirst($request->input('ville')),
                    'code_postal'     => $request->input('code_postal'),
                    'telephone'       => $request->input('telephone'),
                    'email_contact'   => $request->input('email_contact'),
                    ]
                );


            return back()->with('success', 'Votre profil a bien été mise à jour');
        }
        return redirect('/profile')->with('message', $this->messages['erreur_acces']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**
         * Vérifie si l'utilisateur demandé est bien celui connecté
         */
        if (intval($id) === Auth::user()->id) {
            $role = $this->role(); // On récupère la catégorie à laquelle appartient l'utilisateur

            /**
             * Traitement de suppression de la catégorie de rattachement
             */
            if ($role === 'parents') {
                Parents::find(Auth::user()->categorie_id)->delete();
            } elseif ($role === 'assistante-maternelle') {
                AssistantesMaternelles::find(Auth::user()->categorie_id)->delete();
            }

            User::find(Auth::user()->id)->delete();
            return redirect('/')->with('message', "Votre compte a bien été supprimé");
        } else {
            return redirect('/profile')->with('message', $this->messages['erreur_acces']);
        }
    }
}
