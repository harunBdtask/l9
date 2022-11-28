<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCuttingFloorPlanPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cutting_floor_plan_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cutting_floor_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->boolean('is_locked')->default(0);
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
        Schema::dropIfExists('user_cutting_floor_plan_permissions');
    }
}
