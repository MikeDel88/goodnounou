<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Enfant;
use Illuminate\Support\Collection;
use App\Models\AssistantesMaternelles;
use App\Models\Parents;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Image;

class UserController extends Controller
{

    private array $data = [];
    
    /**
     * __construct
     * Utilise le middleware auth pour récupérer les informations de l'utilisateur
     * @return void
     */
    public function __construct(){
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
        $this->data['role'] = $this->role();

        if($this->data['role'] === 'parents'){
            $this->data['enfants'] = Enfant::where('parent_id', Auth::user()->categorie->id)->get();
        }
        
        $this->data['title'] = 'Profile utilisateur';
        return view('profil', $this->data);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo "voir l'utilisateur $id";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        /**
         * Vérifie si l'utilisateur demandé est bien celui connecté
         */
        if(intval($user) === Auth::user()->id){
            $this->data['role'] = $this->role();
            return view('profil_edit', $this->data);
        }

        return redirect('/profile')->with('message', "Cette page n'est pas autorisé");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user)
    {
        /**
         * Vérifie si l'utilisateur demandé est bien celui connecté
         */
        if(intval($user) === Auth::user()->id){
  
            Validator::make($request->input(), [
                'photo'             => 'nullable|image|mimes:jpeg,png,jpg|size:360',
                'nom'               => 'bail|required',
                'prenom'            => 'bail|required',
                'date_naissance'    => 'date_format:"Y-m-d"|before_or_equal:today',
                'adresse'           => 'required',
                'ville'             => 'required',
                'code_postal'       => 'min:5|max:5',
                'telephone'         => 'nullable|max:10',
                'email_contact'     => 'nullable|email'
            ])->validate();

            /**
             * Si l'utilisateur a renseigné une photo, alors on stocke l'image dans un dossier images et un dossier avec le numéro de l'utilisateur
             */
            if($request->file('photo') !== null){

                $extension = $request->file('photo')->extension();
                $path = $request->file('photo')->storeAs("public/images/$user", "avatar.$extension");
                $url = Storage::url($path);

                User::where('id', $user)
                ->update([
                  'photo' => $url,
                ]);

            }
                
            User::where('id', $user)
              ->update([
                  'nom'             =>  ucFirst($request->input('nom')),
                  'prenom'          =>  ucFirst($request->input('prenom')),
                  'date_naissance'  =>  $request->input('date_naissance'),
                  'adresse'         => $request->input('adresse'),
                  'ville'           => ucFirst($request->input('ville')),
                  'code_postal'     => $request->input('code_postal'),
                  'telephone'       => $request->input('telephone'),
                  'email_contact'   => $request->input('email_contact'),
              ]);
   
            
            return back()->with('success', 'Votre profil a bien été mise à jour');
        }
        return redirect('/profile')->with('message', "Cette page n'est pas autorisé");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**
         * Vérifie si l'utilisateur demandé est bien celui connecté
         */
        if(intval($id) === Auth::user()->id){
            $role = $this->role(); // On récupère la catégorie à laquelle appartient l'utilisateur

            /**
             * Traitement de suppression de la catégorie de rattachement
             */
            if($role === 'parents'){
                Parents::find(Auth::user()->categorie_id)->delete();
            }elseif($role === 'assistante-maternelle'){
                AssistantesMaternelles::find(Auth::user()->categorie_id)->delete();
            }
            
            User::find(Auth::user()->id)->delete();
            return redirect('/')->with('message', "Votre compte a bien été supprimé");

        }else{
            return redirect('/profile')->with('message', "Cette page n'est pas autorisé");
        }
    }
}
