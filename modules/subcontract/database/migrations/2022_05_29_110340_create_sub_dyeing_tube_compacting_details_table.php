<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingTubeCompactingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_tube_compacting_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_dyeing_tube_compacting_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('order_no')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->unsignedBigInteger('batch_details_id')->nullable();
            $table->unsignedBigInteger('order_details_id')->nullable();
            $table->date('production_date');
            $table->text('fabric_description')->nullable();
            $table->unsignedInteger('fabric_composition_id')->nullable();
            $table->unsignedInteger('fabric_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->unsignedInteger('dia_type_id')->nullable();
            $table->string('gsm')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('color_type_id')->nullable();
            $table->string('req_order_qty')->nullable();
            $table->string('fin_no_of_roll')->nullable();
            $table->string('finish_qty')->nullable();
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
        Schema::dropIfExists('sub_dyeing_tubler_details');
    }
}
