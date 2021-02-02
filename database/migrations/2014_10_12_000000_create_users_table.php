<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::disableForeignKeyConstraints();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->morphs('categorie');
            $table->string('email')->unique();
            $table->string('email_contact')->nullable();
            $table->string('nom', 25)->nullable();
            $table->string('prenom', 25)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal', 5)->nullable();
            $table->string('photo')->nullable();
            $table->string('telephone', 10)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
