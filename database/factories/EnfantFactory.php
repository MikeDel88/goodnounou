<?php

namespace Database\Factories;

use App\Models\Enfant;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnfantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Enfant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'prenom' => $this->faker->firstName,
            'date_naissance' => $this->faker->dateTimeBetween($startDate = '-6 years', $endDate = 'now', $timezone = null),
        ];
    }
}
