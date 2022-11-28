<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('registration_no');
            $table->integer('factory_id');
            $table->string('name');
            $table ->string('accessories_name');
            $table->float('quantity',9,3);
            $table->string('out_time');
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
        Schema::dropIfExists('employee_tracking');
    }
}
