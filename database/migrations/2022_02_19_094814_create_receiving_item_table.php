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
        Schema::create('receiving_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiving_id');
            $table->double('width');
            $table->double('height');
            $table->double('area');
            $table->double('price');
            $table->double('sum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receiving_item');
    }
};
