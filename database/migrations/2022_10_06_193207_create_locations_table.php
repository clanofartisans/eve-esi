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
        Schema::create('locations', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id');
            $table->string('region_name');
            $table->unsignedBigInteger('system_id');
            $table->string('system_name');
            $table->enum('location_type', ['station', 'structure']);
            $table->unsignedBigInteger('location_id')->primary();
            $table->string('name');
            $table->decimal('security_status', 2, 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
};
