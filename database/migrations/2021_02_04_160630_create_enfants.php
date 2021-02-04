<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnfants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enfants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')
            ->references('id')
            ->on('parents')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->date('date_naissance')->nullable();
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
        Schema::dropIfExists('enfants');
    }
}
