<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class createAssistantesMaternellesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('assistantes_maternelles', function (Blueprint $table) {
            $table->id();
            $table->decimal('lat', 10,8)->nullable();
            $table->decimal('lng', 11,8)->nullable();
            $table->boolean('visible')->default(0);
            $table->date('date_debut')->nullable();
            $table->string('formation', 100)->nullable();
            $table->text('description')->nullable();
            $table->boolean('disponible')->default(0);
            $table->date('prochaine_disponibilite')->nullable();
            $table->string('nombre_place',20)->default(0);
            $table->decimal('taux_horaire', 5,2)->nullable();
            $table->decimal('taux_entretien', 5,2)->nullable();
            $table->decimal('frais_repas', 5,2)->nullable();
            $table->string('adresse_pro', 100)->nullable();
            $table->string('ville_pro', 100)->nullable();
            $table->string('code_postal_pro', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assistantes_maternelles');
    }
}
