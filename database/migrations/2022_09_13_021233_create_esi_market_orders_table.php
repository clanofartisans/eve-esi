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
        Schema::create('esi_market_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->primary();
            $table->enum('order_type', ['buy', 'sell']);
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('system_id');
            $table->enum('location_type', ['station', 'structure']);
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('type_id');
            $table->decimal('price', 16, 2);
            $table->enum('range', ['station', 'region', 'solarsystem', 1, 2, 3, 4, 5, 10, 20, 30, 40]);
            $table->unsignedSmallInteger('duration');
            $table->dateTime('issued');
            $table->integer('min_volume');
            $table->integer('volume_remain');
            $table->integer('volume_total');
            $table->char('hash', 32);

            $table->index('region_id');
            $table->index('location_id');
            $table->index('type_id');
            $table->index('hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esi_market_orders');
    }
};
