<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Parents;
use App\Models\AssistantesMaternelles;
use App\Models\Critere;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        /** 
         * Validation des données renseignées pour l'inscription de l'utilisateur
         */
        Validator::make($input, [
            'categorie'             => ['bail','filled','required', Rule::in(['parents','assistante-maternelle'])],
            'email'                 => 'bail|filled|required|email|unique:users',
            'password'              => 'bail|filled|required|confirmed|min:8|max:16',
            'password_confirmation' => 'bail|filled|required',
            'acceptCG'              => 'accepted'
        ])->validate();

        /**
         * Enregistrement dans la catégorie selectionné par l'utilisateur
         */
        if($input['categorie'] === 'parents'){
            $model = 'App\Models\Parents';
            $cat = Parents::create([]);
        }elseif($input['categorie'] === 'assistante-maternelle'){
            $model = 'App\Models\AssistantesMaternelles';
            $cat = AssistantesMaternelles::create([]);
            Critere::create(['assistante_maternelle_id' => $cat->id]);
        }

        /**
         * Création de l'utilisateur
         */
        $user = User::create([
            'email'             => $input['email'],
            'password'          => Hash::make($input['password']),
            'categorie_type'    => $model,
            'categorie_id'      => $cat->id,
        ]);


        return $user;
    }
}
