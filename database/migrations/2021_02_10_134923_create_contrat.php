<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContrat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')
            ->references('id')
            ->on('parents')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->unsignedBigInteger('enfant_id');
            $table->foreign('enfant_id')
            ->references('id')
            ->on('enfants')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->unsignedBigInteger('assistante_maternelle_id');
            $table->foreign('assistante_maternelle_id')
            ->references('id')
            ->on('assistantes_maternelles')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->string('status')->default('En attente');
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
        Schema::dropIfExists('contrat');
    }
}
