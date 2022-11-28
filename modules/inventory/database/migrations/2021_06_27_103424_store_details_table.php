<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoreDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->string('location', 30);
            $table->unsignedInteger('store_id');
            $table->string('floor', 30);
            $table->unsignedInteger('floor_sequence')->nullable();
            $table->string('room', 30)->nullable();
            $table->unsignedInteger('room_sequence')->nullable();
            $table->string('rack', 30)->nullable();
            $table->unsignedInteger('rack_sequence', )->nullable();
            $table->string('shelf', 30)->nullable();
            $table->unsignedInteger('shelf_sequence')->nullable();
            $table->string('bin', 30)->nullable();
            $table->unsignedInteger('bin_sequence')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_details');
    }
}
