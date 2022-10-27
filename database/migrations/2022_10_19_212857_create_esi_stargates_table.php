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
        Schema::create('esi_stargates', function (Blueprint $table) {
            $table->unsignedBigInteger('stargate_id')->primary();
            $table->unsignedBigInteger('origin_id')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->boolean('secure')->nullable();
            $table->char('hash', 32);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esi_stargates');
    }
};
