<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoraires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('horaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrat_id');
            $table->foreign('contrat_id')
            ->references('id')
            ->on('contrats')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->date('jour_garde');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('depose_par')->nullable();
            $table->string('recupere_par')->nullable();
            $table->string('nombre_heures');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['contrat_id', 'jour_garde']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horaires');
    }
}
