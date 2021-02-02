<?php

namespace Tests\Unit;

use Tests\TestCase;

class AccueilControllerTest extends TestCase
{

    /**
     * Test de la vue accueil
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        
        $response->assertViewHas('title', 'accueil');
        $content = "Site de recherche d'assistantes maternelles par géolocalisation, gestion et suivi personnalisé des enfants à travers un planning et un carnet de bord";
        $response->assertSee($content);
    }

}
