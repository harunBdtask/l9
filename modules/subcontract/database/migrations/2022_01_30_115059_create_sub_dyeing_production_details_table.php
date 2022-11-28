<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_production_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_dyeing_production_id');
            $table->unsignedBigInteger('order_id');
            $table->string('order_no');
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->unsignedBigInteger('batch_details_id')->nullable();
            $table->date('production_date');
            $table->unsignedBigInteger('fabric_composition_id')->nullable();
            $table->unsignedBigInteger('fabric_type_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->unsignedBigInteger('color_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->tinyInteger('dia_type_id')->nullable();
            $table->string('gsm')->nullable();
            $table->string('batch_qty')->nullable();
            $table->string('no_of_roll')->nullable();
            $table->string('dyeing_production_qty')->nullable();
            $table->string('reject_roll_qty')->nullable();
            $table->string('reject_qty')->nullable();
            $table->string('unit_cost')->nullable();
            $table->string('total_cost')->nullable();
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
        Schema::dropIfExists('sub_dyeing_production_details');
    }
}
