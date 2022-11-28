<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDyeingProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyeing_production_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dyeing_production_id');
            $table->unsignedBigInteger('dyeing_order_id');
            $table->string('dyeing_order_no');
            $table->string('dyeing_order_detail_id');
            $table->unsignedBigInteger('dyeing_batch_id')->nullable();
            $table->string('dyeing_batch_no')->nullable();
            $table->unsignedBigInteger('dyeing_batch_detail_id')->nullable();
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
        Schema::dropIfExists('dyeing_production_details');
    }
}
