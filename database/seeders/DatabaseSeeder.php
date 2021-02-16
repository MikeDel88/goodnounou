<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Parents;
use App\Models\Critere;
use App\Models\Status;
use App\Models\AssistantesMaternelles;




class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory()->count(1)->for(
        //     Parents::factory(), 'categorie'
        // )->create();
        Status::insert([
            'nom' => 'en attente'
        ]);
        Status::insert([
            'nom' => 'en cours'
        ]);
        Status::insert([
            'nom' => 'refus'
        ]);
        User::factory()->count(30)->create();
        Critere::factory()->count(15)->create();
        
    }
}
