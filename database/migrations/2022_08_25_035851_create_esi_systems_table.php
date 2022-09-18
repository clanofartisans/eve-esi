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
        Schema::create('esi_systems', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('constellation_id');
            $table->unsignedBigInteger('system_id')->primary();
            $table->string('name');
            $table->decimal('position_x', 25, 0);
            $table->decimal('position_y', 25, 0);
            $table->decimal('position_z', 25, 0);
            $table->string('security_class')->nullable();
            $table->decimal('security_status', 27, 25);
            $table->unsignedBigInteger('star_id')->nullable();
            $table->char('hash', 32);

            $table->unique(['region_id', 'system_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esi_systems');
    }
};
