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
        Schema::create('esi_types', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('type_id')->primary();
            $table->string('name');
            $table->text('description');
            $table->string('capacity')->nullable();
            $table->string('graphic_id')->nullable();
            $table->string('icon_id')->nullable();
            $table->unsignedBigInteger('market_group_id')->nullable();
            $table->string('mass')->nullable();
            $table->string('packaged_volume')->nullable();
            $table->string('portion_size')->nullable();
            $table->string('radius')->nullable();
            $table->string('volume')->nullable();
            $table->boolean('published');
            $table->char('hash', 32);

            $table->index('group_id');
            $table->index('market_group_id');
            $table->index('name');
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
        Schema::dropIfExists('esi_types');
    }
};
