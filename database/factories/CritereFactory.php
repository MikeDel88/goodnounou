<?php

namespace Database\Factories;

use App\Models\AssistantesMaternelles;
use App\Models\Critere;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;


class CritereFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Critere::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $order = 1;
        $boolean = [0, 1];


        return [
            'assistante_maternelle_id' => $order++,
            'week_end' => Arr::random($boolean),
            'ferie' => Arr::random($boolean),
            'horaires_atypique' => Arr::random($boolean),
            'pas_animaux' => Arr::random($boolean),
            'lait_maternelle' => Arr::random($boolean),
            'couches_lavable' => Arr::random($boolean),
            'pas_deplacements' => Arr::random($boolean),
            'periscolaire' => Arr::random($boolean),
            'non_fumeur' => Arr::random($boolean),
            'repas' => Arr::random($boolean)
        ];
    }
}
