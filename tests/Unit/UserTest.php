<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;

class UserTest extends TestCase
{
    

    /**
     * Test le nombre d'entrées après le seed
    * @return void
    */
    public function test_return_user()
    {
       $this->assertDatabaseCount('users', 30);
    }
    
}
