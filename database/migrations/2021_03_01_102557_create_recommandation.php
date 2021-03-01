<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommandation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommandations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')
            ->references('id')
            ->on('parents')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->unsignedBigInteger('assistante_maternelle_id');
            $table->foreign('assistante_maternelle_id')
            ->references('id')
            ->on('assistantes_maternelles')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->smallInteger('note')->nullable();
            $table->text('avis')->nullable();
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
        Schema::dropIfExists('recommandations');
    }
}
