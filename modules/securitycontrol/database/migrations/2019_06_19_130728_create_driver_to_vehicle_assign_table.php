<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverToVehicleAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_to_vehicle_assign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factory_id');
            $table->integer('driver_id');
            $table->integer('vehicle_id');
            $table->string('from');
            $table->string('to');
            $table->string('in_time')->nullable();
            $table->string('out_time')->nullable();
            $table->integer('assign_person');
            $table->time('travel_time')->nullable();;
            $table->softDeletes();
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
        Schema::dropIfExists('driver_to_vehicle_assign');
    }
}
