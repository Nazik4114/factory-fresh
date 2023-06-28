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
        Schema::create('receiving', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_point_id')->nullable();
            $table->foreignId('delivery_point_locality_id');
            $table->string('phone');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('street')->nullable();
            $table->text('house')->nullable();
            $table->text('entrance')->nullable();
            $table->text('apartment')->nullable();
            $table->text('comment')->nullable();
            $table->integer('day');
            $table->time('start');
            $table->time('finish');
            $table->integer('quantity');
            $table->double('total_area')->default(0);
            $table->double('total_items_amount')->default(0);
            $table->double('overlock_price')->default(0);
            $table->double('overlock_length')->default(0);
            $table->double('repair_price')->default(0);
            $table->double('total_price')->default(0);
            $table->foreignId('delivery_point_hour_id')->nullable();
            $table->boolean('repair')->default(false);
            $table->enum('status', ['pending', 'received', 'cleaning', 'done', 'given_to_client', 'canceled'])
                ->default('pending');
            $table->enum('order_type', ['online', 'reception'])->default('online');
            $table->integer('issuance_day')->nullable();
            $table->integer('issuance_point_hour_id')->nullable();
            $table->time('issuance_start')->nullable();
            $table->time('issuance_finish')->nullable();
            $table->timestamp('issuance_date')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('sum')->nullable();
            $table->integer('delivery_price')->nullable();
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
        Schema::dropIfExists('receiving');
    }
};
