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
        Schema::create('esi_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->primary();
            $table->string('name');
            $table->boolean('published');
            $table->char('hash', 32);

            $table->index('published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esi_categories');
    }
};
