<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $array = ['App\Models\AssistantesMaternelles', 'App\Models\Parents'];
        $categorie = Arr::random($array);
        
        return [
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'categorie_type' => $categorie,
            'categorie_id' => $categorie::factory(),
            'password' => Hash::make('test1988'), // password
            'remember_token' => Str::random(10),
        ];
    }
}
