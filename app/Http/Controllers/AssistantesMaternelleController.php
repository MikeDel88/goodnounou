<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AssistantesMaternelles;
use App\Models\Critere;
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

    public function showCard($userId)
    {
        $id = intval($userId);
        $this->data['role'] = 'parents';
        $this->data['renseignements'] = User::findOrfail($id);
        $criteres = DB::table('criteres')->select('*')
            ->where('assistante_maternelle_id', $id)->get();
        $this->data['criteres'] = (array) $criteres[0];
        return view('presentation', $this->data);
    }
    
    /**
     * editCard
     * Montre la fiche d'une assistante maternelle
     * @param  mixed $user
     * @return void
     */
    public function editCard($user)
    {
        if(intval($user) === Auth::user()->categorie->id){

            $critere = DB::table('criteres')->where('assistante_maternelle_id', Auth::user()->categorie->id)->get();
            $this->data['critere'] = $critere[0];
            $this->data['js'][] = 'fiche';
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
        if(intval($user) === Auth::user()->categorie->id){

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

            $coordonnees = $this->coordonnees($request, $request->input('adresse_pro'), $request->input('code_postal_pro'), $request->input('ville_pro'));

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

        // Mettre une alerte si l'adresse n'est pas reconnu
        $data = [];
        $data['lat'] = $json_data[0]['lat'] ?? null;
        $data['lng'] = $json_data[0]['lon'] ?? null;
                
        return $data;
    }


}
