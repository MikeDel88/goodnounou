<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
        * @return void
        */
    public function test_return_user()
    {
        
        $user = User::first();
        $categorie = $user->categorie;

        $this->assertDatabaseCount('users', 1);
        $this->assertEquals( $user->email, 'goodnounou@yopmail.com');
        $this->assertEquals( get_class($categorie), 'App\Models\Parents');
        $this->assertEquals( $user->categorie_id, $categorie->id);

    }
}
