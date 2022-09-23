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
        Schema::create('esi_constellations', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('constellation_id')->primary();
            $table->string('name');
            $table->decimal('position_x', 25, 0);
            $table->decimal('position_y', 25, 0);
            $table->decimal('position_z', 25, 0);
            $table->char('hash', 32);

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
        Schema::dropIfExists('esi_constellations');
    }
};
