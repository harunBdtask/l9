<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnReceiveReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_receive_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('receive_return_id');
            $table->unsignedInteger('uom_id');
            $table->unsignedInteger('yarn_count_id');
            $table->unsignedInteger('yarn_composition_id');
            $table->unsignedInteger('yarn_type_id');
            $table->string('yarn_color')->nullable();
            $table->string('yarn_lot', 30)->comment('Lot No/Yarn Count');

            $table->string('return_qty', 20)->comment('Return Qty');
            $table->string('rate', 20)->nullable();
            $table->string('return_value', 20)->nullable();

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
        Schema::dropIfExists('yarn_receive_return_details');
    }
}
