<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingPeachDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_peach_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sub_dyeing_peach_id');
            $table->unsignedInteger('sub_dyeing_batch_id')->nullable();
            $table->unsignedInteger('sub_dyeing_batch_no')->nullable();
            $table->unsignedInteger('sub_dyeing_batch_details_id')->nullable();
            $table->unsignedInteger('sub_textile_order_id')->nullable();
            $table->unsignedInteger('sub_textile_order_no')->nullable();
            $table->unsignedInteger('sub_textile_order_details_id')->nullable();
            $table->unsignedInteger('fabric_composition_id')->nullable();
            $table->unsignedInteger('fabric_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->unsignedInteger('dia_type_id')->nullable();
            $table->string('gsm')->nullable();
            $table->text('fabric_description')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('color_type_id')->nullable();
            $table->string('batch_qty')->nullable();
            $table->string('order_qty')->nullable();
            $table->string('no_of_roll')->nullable();
            $table->string('finish_qty')->nullable();
            $table->string('reject_roll')->nullable();
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
        Schema::dropIfExists('sub_dyeing_peach_details');
    }
}
