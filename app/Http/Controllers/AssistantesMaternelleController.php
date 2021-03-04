<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AssistantesMaternelles;
use App\Models\Critere;
use App\Models\Favoris;
use App\Models\Recommandations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

/**
 * AssistantesMaternelleController
 */
class AssistantesMaternelleController extends Controller
{
    private array $_data = [];
    private array $_messages = [];

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_data['role'] = 'assistante-maternelle';
        $this->_messages = [
            'validation' => 'Vos informations ont bien été enregistrées',
            'validation_warning' => 'Attention, Les informations sont enregistrées mais l\'adresse que vous avez renseigné ne permet pas la géolocalisation',
        ];
    }

    /**
     * ShowCard
     * Affiche la fiche de renseigments d'une assistante maternelle pour les parents
     *
     * @param mixed $userId Id
     *
     * @return void
     */
    public function showCard($userId)
    {
        $id = intval($userId);
        $this->_data['role'] = 'parents';
        $this->_data['js'][] = 'favoris';
        $this->_data['js'][] = 'recommandation';
        $this->_data['js'][] = 'avis';


        $criteres = DB::table('criteres')
            ->select('*')
            ->where('assistante_maternelle_id', $id)
            ->first();
        $renseignements = User::where('categorie_id', $id)
            ->where('categorie_type', 'App\Models\AssistantesMaternelles')
            ->first();
        $favoris = Favoris::where('parent_id', Auth::user()->categorie->id)
            ->where('assistante_maternelle_id', $id)
            ->first();
        $recommandation = Recommandations::where('parent_id', Auth::user()->categorie->id)
            ->where('assistante_maternelle_id', $id)
            ->first();
        $nombreAvis = Recommandations::where('assistante_maternelle_id', $renseignements->categorie->id)
            ->whereNotNull('avis')
            ->count();
        $nombreNote = Recommandations::where('assistante_maternelle_id', $renseignements->categorie->id)
            ->whereNotNull('note')
            ->count();

        $this->_data['noteMax']          = 5;
        $this->_data['nombreNote']       = $nombreNote;
        $this->_data['nombreAvis']       = $nombreAvis;
        $this->_data['moyenne']          = $renseignements->categorie->recommandations->avg('note');
        $this->_data['recommandation']   = $recommandation;
        $this->_data['favoris']          = (isset($favoris)) ? true : false;
        $this->_data['renseignements']   = $renseignements;
        $this->_data['criteres']         = (array) $criteres;

        if ($renseignements !== null) {
            return view('presentation', $this->_data); // retourne la vue
        } else {
            return abort(404); // Sinon retourne une erreur 404 page introuvable
        }
    }

    /**
     * EditCard
     * Montre la fiche d'une assistante maternelle
     *
     * @param mixed $user User
     *
     * @return void
     */
    public function editCard($user)
    {
        // Vérifie que l'utilisateur demandé est bien celui qui est connecté
        if (intval($user) === Auth::user()->categorie->id) {
            $critere = DB::table('criteres')
                ->where('assistante_maternelle_id', Auth::user()->categorie->id)
                ->get();
            $this->_data['critere'] = $critere[0];
            $this->_data['js'][] = 'fiche';

            return view('fiche', $this->_data);
        }
        return redirect('/profile')
            ->with('message', $this->messages['erreur']);
    }

    /**
     * UpdateCard
     *  Met à jour les informations professionnelles
     * et enregistre les coordonnées de géolocalisation
     *
     * @param mixed $request Requête
     * @param mixed $user    Id
     *
     * @return void
     */
    public function updateCard(Request $request, $user)
    {
        // Vérifie que l'utilisateur demandé est bien celui qui est connecté
        if (intval($user) === Auth::user()->categorie->id) {

            /**
             * Validation des données
             */
            Validator::make(
                $request->input(),
                [
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
                ]
            )->validate();

            /**
             * Récupère les coordonnées géographique de l'adresse
             *pour permettre les recherches
             */
            $coordonnees = $this->coordonnees($request, $request->input('adresse_pro'), $request->input('code_postal_pro'), $request->input('ville_pro'));

            /**
             * Mise à jour de l'utilisateur
             */
            AssistantesMaternelles::where('id', $user)
                ->update(
                    [
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
                    ]
                );

            /**
             * Condition si les coordonnées géographique n'ont pas été trouvé
             * pour permettre la géolocalisation
             */
            if ($coordonnees['lat'] !== null && $coordonnees['lng'] !== null) {
                return back()
                    ->with('success', $this->_messages['validation']);
            } else {
                return back()
                    ->with('message', $this->_messages['validation_warning']);
            }
        } else {
            return redirect('/profile')
                ->with('message', $this->messages['erreur']);
        }
    }

    /**
     * Coordonnees
     *  Transforme une adresse en coordonnees lat et lon
     *
     * @param mixed $request     Requête
     * @param mixed $adresse     Adresse
     * @param mixed $code_postal CodePostal
     * @param mixed $ville       Ville
     *
     * @return array
     */
    public function coordonnees(Request $request, string $adresse, string $code_postal, string $ville)
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
