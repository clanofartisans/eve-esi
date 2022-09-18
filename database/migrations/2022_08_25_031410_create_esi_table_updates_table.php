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
        Schema::create('esi_table_updates', function (Blueprint $table) {
            $table->id();
            $table->string('table');
            $table->string('section');
            $table->unsignedBigInteger('data_id');
            $table->char('hash', 32);
            $table->json('data');

            $table->index(['table', 'section']);
            $table->index('data_id');
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
        Schema::dropIfExists('esi_table_updates');
    }
};
