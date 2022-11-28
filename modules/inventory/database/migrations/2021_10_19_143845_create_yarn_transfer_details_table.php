<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('yarn_transfer_id');

            $table->string('item_description')->nullable();
            $table->unsignedInteger('yarn_count_id');
            $table->unsignedInteger('yarn_composition_id');
            $table->unsignedInteger('yarn_type_id');
            $table->string('yarn_color')->nullable();
            $table->string('yarn_lot', 30);
            $table->string('yarn_brand')->nullable();
            $table->unsignedInteger('uom_id');

            $table->string('transfer_qty', 20);
            $table->string('rate', 20);
            $table->string('transfer_value', 20);

            $table->unsignedInteger('store_id');
            $table->unsignedInteger('floor_id')->nullable();
            $table->unsignedInteger('room_id')->nullable();
            $table->unsignedInteger('rack_id')->nullable();
            $table->unsignedInteger('shelf_id')->nullable();
            $table->unsignedInteger('bin_id')->nullable();

            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('yarn_transfer_details');
    }
}
