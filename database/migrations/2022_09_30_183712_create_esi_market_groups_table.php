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
        Schema::create('esi_market_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('market_group_id')->primary();
            $table->unsignedBigInteger('parent_group_id')->nullable();
            $table->string('name');
            $table->string('description');
            $table->char('hash', 32);

            $table->index('parent_group_id');
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
        Schema::dropIfExists('esi_market_groups');
    }
};
