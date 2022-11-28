<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRackCartonPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rack_carton_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('warehouse_floor_id')->index();
            $table->unsignedInteger('warehouse_rack_id')->index();
            $table->integer('position_no');
            $table->unsignedInteger('warehouse_carton_id')->nullable()->index();
            $table->unsignedInteger('factory_id')->index();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('rack_carton_positions');
    }
}
