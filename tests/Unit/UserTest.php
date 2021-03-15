<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\AssistantesMaternelles;
use App\Models\Parents;
use App\Models\Critere;

/**
 * UserTest
 */
class UserTest extends TestCase
{


    /**
     * Test l'enregistrement d'un nouvel utilisateur
     *
     * @return void
     */
    public function testSeeder()
    {
        $users = User::all();
        foreach ($users as $user) {
            $this->assertNotNull($user->categorie_type);
            $this->assertNotNull($user->categorie_id);
            $this->assertNotNull($user->categorie->id);
            $this->assertNotNull($user->email);
            $this->assertNotNull($user->password);
        }
    }

    /**
     * TestNombreAssMatEtCriteres Vérifie qu'il y a autant d'enregistrement critere que d'Assistante Maternelle créée
     *
     * @return void
     */
    public function testNombreAssMatEtCriteres()
    {
        $assistantesMaternelles = AssistantesMaternelles::all();
        $criteres = Critere::all();
        $this->assertEquals($assistantesMaternelles->count(), $criteres->count());
    }

    /**
     * TestNombreUserAsAssistantesMaternelles Test si le nombre de categorie correspond au nombre d'enregistrement dans la table associé
     *
     * @return void
     */
    public function testNombreUserAsAssistantesMaternelles()
    {
        $user = User::where('categorie_type', 'App\Models\AssistantesMaternelles');
        $this->assertEquals(AssistantesMaternelles::all()->count(), $user->count());
    }

    /**
     * TestNombreUserAsParents Test si le nombre de categorie correspond au nombre d'enregistrement dans la table associé
     *
     * @return void
     */
    public function testNombreUserAsParents()
    {
        $user = User::where('categorie_type', 'App\Models\Parents');
        $this->assertEquals(Parents::all()->count(), $user->count());
    }


}
