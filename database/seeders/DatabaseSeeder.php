<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Parents;
use App\Models\Critere;
use App\Models\Status;
use App\Models\Recommandations;
use App\Models\AssistantesMaternelles;
use Faker\Generator;





class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Status::insert(['nom' => 'en attente']);
        Status::insert(['nom' => 'en cours']);
        Status::insert(['nom' => 'refus']);
        Status::insert(['nom' => 'clos']);
        User::factory()->count(100)->create();
        $assMat = User::where('categorie_type', 'App\Models\AssistantesMaternelles');
        Recommandations::factory()->count(1500)->create();
        Critere::factory($assMat->count())->create();
    }
}
