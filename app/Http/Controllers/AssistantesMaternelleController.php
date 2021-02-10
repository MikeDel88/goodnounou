<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AssistantesMaternelles;
use App\Models\Critere;
use App\Models\Favoris;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class AssistantesMaternelleController extends Controller
{
    private array $data = [];

    public function __construct()
    {
        $this->data['role'] = 'assistante-maternelle';
    }
    
    /**
     * showCard
     * Affiche la fiche de renseigments d'une assistante maternelle pour les parents
     * @param  mixed $userId
     * @return void
     */
    public function showCard($userId)
    {
        $id = intval($userId); // Transforme la données en entier
        $this->data['role'] = 'parents'; // Je défini que le rôle de l'utilisateur doit être un parent.
        $this->data['js'][] = 'favoris'; // Chargement du script spécifique pour gérer les favoris en asynchrone
        
        $criteres = DB::table('criteres')->select('*')->where('assistante_maternelle_id', $id)->get(); // Récupère l'ensemble des critères associé à l'assistante maternelle
        $renseignements = User::where('categorie_id', $id)->where('categorie_type', 'App\Models\AssistantesMaternelles')->get(); // Récupère la liste des informations de l'utilisateur nounou
        $favoris = Favoris::where('parent_id', Auth::user()->categorie->id)->where('assistante_maternelle_id', $id)->get(); // Cela doit retourner un seul résultat maximum
        
        $this->data['favoris'] = (isset($favoris[0])) ? true : false; // Si la requête à renvoyé un objet dans le tableau, alors il existe un favoris   
        $this->data['renseignements'] = $renseignements[0]; // Récupère la seule ligne sur la base de données doit retourner
        $this->data['criteres'] = (array) $criteres[0]; // Transforme l'objet en tableau pour l'exploiter dans la vue
        
        return view('presentation', $this->data); // retourne la vue
    }
    
    /**
     * editCard
     * Montre la fiche d'une assistante maternelle
     * @param  mixed $user
     * @return void
     */
    public function editCard($user)
    {
        // Vérifie que l'utilisateur demandé est bien celui qui est connecté
        if(intval($user) === Auth::user()->categorie->id){

            $critere = DB::table('criteres')->where('assistante_maternelle_id', Auth::user()->categorie->id)->get(); // Sélectionne l'ensemble des critères de l'utilisateur connecté
            $this->data['critere'] = $critere[0]; // Prépare l'objet pour la vue
            $this->data['js'][] = 'fiche'; // Ajout d'un fichier asset spécifique à la vue
            return view('fiche', $this->data);
        }
        return redirect('/profile')->with('message', "Cette page n'est pas autorisé");
    }
    
    /**
     * updateCard
     *  Met à jour les informations professionnelles et enregistre les coordonnées de géolocalisation
     * @param  mixed $request
     * @param  mixed $user
     * @return void
     */
    public function updateCard(Request $request, $user)
    {
        // Vérifie que l'utilisateur demandé est bien celui qui est connecté
        if(intval($user) === Auth::user()->categorie->id){

            /**
             * Validation des données
             */
            Validator::make($request->input(), [
                'date_debut'                    => 'date_format:"Y-m-d"|before_or_equal:today|required',
                'formation'                     => 'string|bail|required',
                'nombre_place'                  => 'integer|required|min:0',
                'adresse_pro'                   => 'string|required',
                'ville_pro'                     => 'string|required',
                'code_postal_pro'               => 'min:5|max:5',
                'taux_horaire'                  => 'numeric|min:0|required',
                'taux_entretien'                => 'numeric|min:0|required',
                'frais_repas'                   => 'numeric|min:0|required',
                'description'                   => 'string|nullable',
                'date_prochaine_disponibilite'  => 'date_format:"Y-m-d"|after_or_equal:today|nullable'
            ])->validate();

            /**
             * Récupère les coordonnées géographique de l'adresse pour permettre les recherches
             */
            $coordonnees = $this->coordonnees($request, $request->input('adresse_pro'), $request->input('code_postal_pro'), $request->input('ville_pro'));

            /**
             * Mise à jour de l'utilisateur
             */
            AssistantesMaternelles::where('id', $user)
                ->update([
                'lat'                     => $coordonnees['lat'],
                'lng'                     => $coordonnees['lng'],
                'date_debut'              => $request->input('date_debut'),
                'formation'               => $request->input('formation'),
                'nombre_place'            => $request->input('nombre_place'),
                'adresse_pro'             => $request->input('adresse_pro'),
                'ville_pro'               => ucFirst($request->input('ville_pro')),
                'code_postal_pro'         => $request->input('code_postal_pro'),
                'taux_horaire'            => $request->input('taux_horaire'),
                'taux_entretien'          => $request->input('taux_entretien'),
                'frais_repas'             => $request->input('frais_repas'),
                'description'             => $request->input('description'),
                'prochaine_disponibilite' => $request->input('date_prochaine_disponibilite')           
            ]);

            /**
             * Condition si les coordonnées géographique n'ont pas été trouvé pour permettre la géolocalisation
             */
            if($coordonnees['lat'] !== null && $coordonnees['lng'] !== null){
                return back()->with('success', 'Vos informations ont bien été enregistrées');
            }else{
                return back()->with('message', "Attention, Les informations sont enregistrées mais l'adresse que vous avez renseigné ne permet pas la géolocalisation");
            }
            
        }else{
            return redirect('/profile')->with('message', "Cette page n'est pas autorisé");
        }
    }

    /**
     * coordonnees
     *  Transforme une adresse en coordonnees lat et lon
     * @param  mixed $adresse
     * @param  mixed $code_postal
     * @param  mixed $ville
     * @return array
     */
    public function coordonnees(Request $request, string $adresse, string $code_postal, string $ville) :array
    {
        /**
         * Récupère l'adresse sur l'API Openstreetmap de façon asynchrone avec Curl
         */
        $adresse = array(
                  'street'     => $adresse,
                  'postalcode' => $code_postal,
                  'ville'      => $ville,
                  'country'    => 'france',
                  'format'     => 'json',
                );

        $url = 'https://nominatim.openstreetmap.org/?' . http_build_query($adresse);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $request->server('HTTP_USER_AGENT'));
        $geopos = curl_exec($ch);
        curl_close($ch);
        $json_data = json_decode($geopos, true);

        $data = [];
        $data['lat'] = $json_data[0]['lat'] ?? null;
        $data['lng'] = $json_data[0]['lon'] ?? null;
                
        return $data;
    }


}
