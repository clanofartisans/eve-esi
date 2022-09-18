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
        Schema::create('esi_regions', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id');
            $table->text('description')->nullable();
            $table->string('name');
            $table->char('hash', 32);

            $table->primary('region_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esi_regions');
    }
};
