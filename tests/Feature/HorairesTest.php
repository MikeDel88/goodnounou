<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tests\TestCase;
use App\Models\User;
use App\Models\Parents;
use App\Models\Enfant;
use App\Models\AssistantesMaternelles;
use App\Models\Contrats;

class HorairesTest extends TestCase
{

    public User $user;
    public Enfant $enfant;
    public AssistantesMaternelles $assistanteMaternelle;
    public Contrats $contrat;


    /**
     * TestCreationEnfantEtContrat
     *
     * @return void
     */
    public function testCreationEnfantEtContrat()
    {

        $this->user = User::where('categorie_type', 'App\Models\Parents')->first(); // Récupère le premier parent
        $this->enfant = Enfant::factory()->create(['parent_id' => $this->user->categorie->id,'nom' => $this->user->nom]);// Création d'un enfant
        $this->assertDatabaseHas('enfants', ['id' => $this->enfant->id,'parent_id' => $this->user->categorie->id]);
        $this->assistanteMaternelle = AssistantesMaternelles::first();
        $this->contrat = Contrats::factory()->create(['parent_id' => $this->user->categorie->id,'enfant_id' => $this->enfant->id,'assistante_maternelle_id' => $this->assistanteMaternelle->id]);
        $this->assertDatabaseHas('contrats', ['enfant_id' => $this->enfant->id,'parent_id' => $this->user->categorie->id,'assistante_maternelle_id' => $this->assistanteMaternelle->id]);

        echo "/Résultat Création Entant et contrat \n";
        echo "Contrat: {$this->contrat->id} \n";
        echo "ParentId: {$this->contrat->parent_id} \n";
        echo "EnfantId: {$this->contrat->enfant_id} \n";
        echo "AssistanteMaternelleId: {$this->contrat->assistante_maternelle_id} \n";
    }

    /**
     * Test creation d'un horaire sur un contrat
     *
     * @depends testCreationEnfantEtContrat
     *
     * @return void
     */
    public function testCreationHoraire()
    {

        $user = User::where('categorie_type', 'App\Models\Parents')->first();
        $contrat = Contrats::latest('id')->first();
        $jourAleatoire = random_int(1, 150);
        $jourGarde = date('Y-m-d', strtotime($contrat->date_debut . ' + '. $jourAleatoire . ' days'));
        $jourGardeFr = date('d/m/Y', strtotime($jourGarde));
        $newHoraire = [
                'contrat_id' => $contrat->id,
                'debut_contrat' => $contrat->date_debut,
                'jour_garde' => $jourGarde,
                'heure_debut' => '09:00',
                'nombre_heures' => '6h00',
                'depose_par' => null,
                'heure_fin' => '18:00',
                'recupere_par' => null,
                'description' => null,
        ];
        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->post('/horaires/ajouter', $newHoraire);
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', "L'horaire pour le $jourGardeFr a bien été enregistré");

        echo "/Résultat Création Horaire \n";
        echo "Contrat: {$contrat->id} \n";
        echo "ParentId: {$contrat->parent_id} \n";
        echo "EnfantId: {$contrat->enfant_id} \n";
        echo "AssistanteMaternelleId: {$contrat->assistante_maternelle_id} \n";

    }
}
