<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegisterVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factory_id');
            $table->string('vehicle_name');
            $table->string('vehicle_model');
            $table->string('vehicle_registration');
            $table->string('vehicle_chassis');
            $table->string('vehicle_engine');
            $table->tinyInteger('vehicle_type');
            $table->tinyInteger('status')->default(false);
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
        Schema::dropIfExists('register_vehicles');
    }
}
