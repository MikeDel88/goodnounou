<?php

namespace App\Actions\Fortify;

use App\Models\User;
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
        Validator::make($input, [
            'category_id' => ['bail','filled','required', 'integer', Rule::in([1,2])],
            'email' => 'bail|filled|required|email|unique:users',
            'password' => 'bail|filled|required|confirmed|min:8|max:16',
            'password_confirmation' => 'bail|filled|required',
            'acceptCG' => 'accepted'
        ])->validate();

        return User::create([
            'category_id' => $input['category_id'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
