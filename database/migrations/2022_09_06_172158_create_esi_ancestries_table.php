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
        Schema::create('esi_ancestries', function (Blueprint $table) {
            $table->unsignedBigInteger('ancestry_id')->primary();
            $table->unsignedBigInteger('bloodline_id');
            $table->string('name');
            $table->text('description');
            $table->string('short_description')->nullable();
            $table->unsignedBigInteger('icon_id')->nullable();
            $table->char('hash', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esi_ancestries');
    }
};
