<?php

namespace Database\Factories;

use App\Models\Horaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class HoraireFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Horaire::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'jour_garde' => $this->faker->dateTimeBetween($startDate = 'now', $endDate = '+1 years', $timezone = null),
            'heure_debut' => $this->faker->time($format = 'H:i:s', $max = '12:00:00'),
            'nombre_heures' => '6h00',
            'heure_fin' => $this->faker->time($format = 'H:i:s', $max = '18:00:00'),
        ];
    }
}
