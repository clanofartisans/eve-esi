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
        Schema::create('esi_structures', function (Blueprint $table) {
            $table->unsignedBigInteger('system_id')->nullable();
            $table->unsignedBigInteger('structure_id')->primary();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->decimal('position_x', 25, 0)->nullable();
            $table->decimal('position_y', 25, 0)->nullable();
            $table->decimal('position_z', 25, 0)->nullable();
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
        Schema::dropIfExists('esi_structures');
    }
};
