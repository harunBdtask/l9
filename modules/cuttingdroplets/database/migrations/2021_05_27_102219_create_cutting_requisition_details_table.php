<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuttingRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutting_requisition_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cutting_requisition_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('fabric_type')->nullable()->comment="fabric_type_id";
            $table->unsignedInteger('color_id');
            $table->unsignedInteger('garments_part_id')->nullable();
            $table->string('batch_no')->nullable();
            $table->unsignedInteger('composition_fabric_id')->nullable();
            $table->double('requisition_amount', 8, 3);
            $table->double('balance_amount', 8, 3)->default(0);
            $table->unsignedInteger('unit_of_measurement_id')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('factory_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('cutting_requisition_id')->references('id')->on('cutting_requisitions')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cutting_requisition_details');
    }
}
