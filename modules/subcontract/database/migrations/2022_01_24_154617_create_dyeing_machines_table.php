<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDyeingMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyeing_machines', function (Blueprint $table) {
            $table->id();
            $table->boolean('floor_type')->comment('1 => Dyeing, 2 => Dye Finishing');
            $table->string('name');
            $table->string('heating_rate');
            $table->string('maximum_working_pressure');
            $table->boolean('status')->comment('2 => INACTIVE, 1 => ACTIVE');
            $table->boolean('type')->comment('1 => Fiber Dyeing M/C, 2 => Yarn Dyeing M/C, 3 => Knit Dyeing M/C, 4 => Dye Finishing M/C');
            $table->string('description');
            $table->string('cooling_rate');
            $table->string('maximum_working_temp');
            $table->string('capacity');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('dyeing_machines');
    }
}
