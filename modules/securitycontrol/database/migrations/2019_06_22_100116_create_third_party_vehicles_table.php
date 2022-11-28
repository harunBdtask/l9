<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThirdPartyVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_party_vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factory_id');
            $table->string('driver_name');
            $table->string('driver_license');
            $table->string('vehicle_name');
            $table->string('vehicle_registration');
            $table->string('purpose');
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
        Schema::dropIfExists('third_party_vehicles');
    }
}
