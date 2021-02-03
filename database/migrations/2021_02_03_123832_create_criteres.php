<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCriteres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('criteres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assistante_maternelle_id');
            $table->foreign('assistante_maternelle_id')
            ->references('id')
            ->on('assistantes_maternelles')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->boolean('week_end')->default(0);
            $table->boolean('ferie')->default(0);
            $table->boolean('horaires_atypique')->default(0);
            $table->boolean('animaux')->default(0);
            $table->boolean('lait_maternelle')->default(0);
            $table->boolean('couches_lavable')->default(0);
            $table->boolean('deplacements')->default(0);
            $table->boolean('periscolaire')->default(0);
            $table->boolean('fumeur')->default(0);
            $table->boolean('repas')->default(0);
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
        Schema::dropIfExists('criteres');
    }
}
