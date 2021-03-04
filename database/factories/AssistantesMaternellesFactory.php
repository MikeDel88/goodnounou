<?php

namespace Database\Factories;

use App\Models\AssistantesMaternelles;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;


class AssistantesMaternellesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssistantesMaternelles::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $boolean = [0,1];
        return [
           'ville_pro' => $this->faker->city,
            'adresse_pro' => $this->faker->streetAddress,
            'code_postal_pro' => str_replace(' ', '', $this->faker->postcode),
            'lat' => $this->faker->latitude($min = 43, $max = 44),
            'lng' => $this->faker->longitude($min = 1, $max = 2),
            'visible' => 1,
            'disponible' => Arr::random($boolean),
        ];
    }

}
