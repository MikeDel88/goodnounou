<?php

namespace Database\Factories;

use App\Models\Contrats;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContratsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contrats::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date_debut' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
            'nombre_heures' => $this->faker->numberBetween($min = 10, $max = 40),
            'nombre_semaines' => $this->faker->numberBetween($min = 1, $max = 47),
            'status_id' => 2,
        ];
    }
}
