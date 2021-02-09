<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Parents;
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
        User::factory()->count(30)->create();
    }
}
