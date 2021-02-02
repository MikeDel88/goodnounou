<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\AssistantesMaternelles;
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
     * role
     * Permet de connaitre la catégorie à laquelle appartient l'utisateur en cours
     * @return void
     */
    private function role()
    {
        return (get_class(Auth::user()->categorie) === 'App\Models\Parents') ? 'parents' : 'assistante-maternelle';
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['role'] = $this->role();
        $this->data['title'] = 'Profile utilisateur';
        return view('profil', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        if(intval($user) === Auth::user()->id){
  
            Validator::make($request->input(), [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|size:360',
            'nom' => 'bail|required',
            'prenom' => 'bail|required',
            'date_naissance' => 'date_format:"Y-m-d"|before_or_equal:today',
            'adresse' => 'required',
            'ville' => 'required',
            'code_postal' => 'min:5|max:5',
            'telephone' => 'nullable|max:10',
            'email_contact' => 'nullable|email'
            ])->validate();

            
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
                  'nom' =>  ucFirst($request->input('nom')),
                  'prenom' =>  ucFirst($request->input('prenom')),
                  'date_naissance' =>  $request->input('date_naissance'),
                  'adresse' => $request->input('adresse'),
                  'ville' => ucFirst($request->input('ville')),
                  'code_postal' => $request->input('code_postal'),
                  'telephone' => $request->input('telephone'),
                  'email_contact' => $request->input('email_contact'),
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
        //
    }
}
