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
        Schema::create('esi_stations', function (Blueprint $table) {
            $table->unsignedBigInteger('system_id');
            $table->unsignedBigInteger('station_id')->primary();
            $table->string('name')->nullable();
            $table->decimal('security_status', 27, 25);
            $table->string('max_dockable_ship_volume')->nullable();
            $table->string('office_rental_cost')->nullable();
            $table->unsignedBigInteger('owner')->nullable();
            $table->decimal('position_x', 25, 0)->nullable();
            $table->decimal('position_y', 25, 0)->nullable();
            $table->decimal('position_z', 25, 0)->nullable();
            $table->unsignedBigInteger('race_id')->nullable();
            $table->string('reprocessing_efficiency')->nullable();
            $table->string('reprocessing_stations_take')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->char('hash', 32)->nullable();

            $table->index('system_id');
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
        Schema::dropIfExists('esi_stations');
    }
};
