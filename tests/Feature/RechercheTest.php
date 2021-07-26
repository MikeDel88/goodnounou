<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\AssistantesMaternelles;

/**
 * RechercheTest
 */
class RechercheTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRecherche()
    {

        $lat1 = 43.32362400;
        $lng1 = 1.07139;
        $distanceTest = 20;
        $criteres = ['lait_maternelle'];

        $response = $this->postJson('/api/recherche', ['lat' => $lat1, 'lng' => $lng1, 'distance' => $distanceTest, 'criteres' => $criteres]);
        $response->assertStatus(200); // Doit retourner un status HTTP correct
        $this->assertNotFalse($response['result']); // La reponse ne doit pas être fausse;
        echo "Matching des distances de recherche \n";
        foreach ($response['result'] as $result) {

            $this->assertLessThan($distanceTest, $result['distance']); // Distance compris dans le rayon demandé
            $this->assertEquals($this->distance($lat1, $lng1, $result['lat'], $result['lng']), $result['distance']); // Test si la fonction SQL fonctionne correctement
            $this->assertEquals('App\Models\AssistantesMaternelles', $result['categorie_type']); // Appartient bien à une assistante maternelle
            echo "{$this->distance($lat1, $lng1, $result['lat'], $result['lng'])} = {$result['distance']} \n";
            $assMat = AssistantesMaternelles::find($result['id']);
            foreach ($criteres as $critere) {
                $this->assertEquals(1, $assMat->criteres->$critere); // Le critère demandé est bien respecté
            }

        }

    }

    /**
     * Distance entre deux points, reprise de la requête SQL
     *
     * @param mixed $lat1 Coordonnées
     * @param mixed $lng1 Coordonnées
     * @param mixed $lat2 Coordonnées
     * @param mixed $lng2 Coordonnées
     *
     * @return float
     */
    public function distance($lat1, $lng1, $lat2, $lng2)
    {
        return 6371 * acos(cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lng2) - deg2rad($lng1)) + sin(deg2rad($lat1)) * sin(deg2rad($lat2)));
    }

}
