<?php

namespace Database\Factories;

use App\Models\Recommandations;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecommandationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recommandations::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'avis' => $this->faker->realText($maxNbChars = 200, $indexSize = 2),
            'note' => $this->faker->numberBetween($min = 1, $max = 5),
            'parent_id' => $this->faker->numberBetween($min = 1, $max = 10),
            'assistante_maternelle_id' => $this->faker->numberBetween($min = 1, $max = 60),
            'created_at' => $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now'),
            'updated_at' => $this->faker->dateTimeBetween($startDate = '-3 years', $endDate = 'now')
        ];
    }
}
