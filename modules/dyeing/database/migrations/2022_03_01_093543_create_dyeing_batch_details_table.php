<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDyeingBatchDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyeing_batch_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dyeing_batch_id');
            $table->unsignedInteger('textile_order_id');
            $table->string('textile_order_no');
            $table->unsignedInteger('textile_order_detail_id');
            $table->unsignedInteger('sub_textile_operation_id');
            $table->unsignedInteger('sub_textile_process_id');
            $table->unsignedInteger('fabric_composition_id');
            $table->unsignedInteger('fabric_type_id')->nullable();
            $table->unsignedInteger('body_part_id')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->unsignedInteger('color_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->unsignedInteger('dia_type_id')->nullable();
            $table->string('gsm')->nullable();
            $table->text('fabric_description')->nullable();
            $table->json('yarn_details')->nullable();
            $table->unsignedInteger('uom_id');
            $table->string('stitch_length')->nullable();
            $table->string('batch_roll')->nullable();
            $table->string('order_qty')->nullable();
            $table->string('batch_weight')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('dyeing_batch_details');
    }
}
