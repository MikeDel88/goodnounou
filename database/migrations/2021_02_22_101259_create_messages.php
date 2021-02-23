<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assistante_maternelle_id');
            $table->foreign('assistante_maternelle_id')
            ->references('id')
            ->on('assistantes_maternelles')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->unsignedBigInteger('enfant_id');
            $table->foreign('enfant_id')
            ->references('id')
            ->on('enfants')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->text('contenu');
            $table->date('jour_garde');
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
        Schema::dropIfExists('messages');
    }
}
