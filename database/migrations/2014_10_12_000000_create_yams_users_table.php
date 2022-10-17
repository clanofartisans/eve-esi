<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yams_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('character_id');
            $table->string('character_owner_hash');
            $table->string('character_name');
            $table->string('current_system')->nullable();
            $table->text('token');
            $table->string('refreshToken');
            $table->unsignedSmallInteger('expiresIn');
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
        Schema::dropIfExists('yams_users');
    }
};
