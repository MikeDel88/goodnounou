<?php

namespace Database\Factories;

use App\Models\AssistantesMaternelles;
use App\Models\Critere;
use Illuminate\Database\Eloquent\Factories\Factory;

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
         
        return [
            'assistante_maternelle_id' => $order++,
        ];
    }
}
