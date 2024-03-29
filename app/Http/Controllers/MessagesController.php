<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * MessagesController
 */
class MessagesController extends Controller
{

    private array $_messages = [];
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_messages = [
            'validation' => 'Message enregistré',
            'modification' => 'Message modifié avec succès'
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['role'] = $this->role();
        if ($this->data['role'] === 'assistante-maternelle') {
            $this->data['contrats'] = Auth::user()->categorie->contrats;
            $this->data['js'][] = "messages_ass_mat";
            return view('messages_ass_mat', $this->data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $this->data['role'] = $this->role();
        if ($this->data['role'] === 'parents') {
            $this->data['enfants'] = Auth::user()->categorie->enfants;
            $this->data['js'][] = "messages_parent";
            return view('messages_parents', $this->data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request Requête
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /**
         ** Validation des données
         */
        Validator::make(
            $request->input(), [
                'enfant' => 'integer|required',
                'jour_garde' => "before_or_equal:today|required",
                'message' => 'string|required',
            ]
        )->validate();

        foreach (Auth::user()->categorie->contrats as $user) {
            $status = ($user->enfant->id === intval($request->input('enfant'))) ? true : false;
            if ($status === true) {
                break;
            }
        }

        if ($status === true) {

            /**
             * Création du message
             */
            try {
                Messages::create(
                    [
                        'assistante_maternelle_id' => Auth::user()->categorie->id,
                        'enfant_id' => $request->input('enfant'),
                        'contenu' => $request->input('message'),
                        'jour_garde' => $request->input('jour_garde'),
                    ]
                );

                return back()->with('success', $this->_messages['validation']);
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {
                    return back()->with('message', $this->messages['erreur']);
                }
            }
        } else {
            return back()->with('message', $this->messages['erreur']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Requête
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /**
         ** Validation des données
         */
        Validator::make(
            $request->input(),
            [
                'enfant' => 'integer|required',
                'id_message' => 'integer|required',
                'contenu' => 'string|required',
            ]
        )->validate();

        // Vérifie si l'id de l'enfant est bien l'un des utilisateurs connecté et avec un contrat en cours
        foreach (Auth::user()->categorie->contrats as $contrat) {
            $status = ($contrat->enfant->id === intval($request->input('enfant'))) ? true : false;
            if ($status === true) {
                break;
            }
        }

        if ($status === true) {

            /**
             * Modification du message par l'assistante maternelle
             */
            try {
                $message = Messages::where('id', $request->input('id_message'))
                    ->where('enfant_id', $request->input('enfant'))
                    ->first();
                $message->contenu = $request->input('contenu');
                $message->save();

                return back()->with('success', $this->_messages['modification']);
            } catch (\Illuminate\Database\QueryException $e) {
                $errorCode = $e->errorInfo[1];
                if ($errorCode == 1062) {
                    return back()->with('message', $this->messages['erreur']);
                }
            }
        } else {
            return back()->with('message', $this->messages['erreur']);
        }
    }
}
