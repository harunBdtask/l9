<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingBatchDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_batch_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('sub_dyeing_batch_id');
            $table->unsignedBigInteger('supplier_id')->comment('party_type=Dyeing/Finishing Supplier');
            $table->unsignedBigInteger('sub_textile_order_id')->nullable();
            $table->unsignedBigInteger('sub_textile_order_detail_id')->nullable();
            $table->unsignedBigInteger('sub_grey_store_id');
            $table->unsignedBigInteger('sub_dyeing_unit_id')->nullable();
            $table->unsignedBigInteger('sub_textile_operation_id')->nullable();
            $table->unsignedBigInteger('sub_textile_process_id')->nullable();
            $table->unsignedBigInteger('fabric_composition_id')->nullable();
            $table->unsignedBigInteger('fabric_type_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->unsignedBigInteger('color_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->tinyInteger('dia_type_id')->comment('1=Open,2=Tubular,3=Needle Open')->nullable();
            $table->string('gsm')->nullable();
            $table->text('material_description')->nullable();
            $table->json('yarn_details')->nullable();
            $table->string('grey_required_qty')->nullable();
            $table->unsignedBigInteger('unit_of_measurement_id')->nullable();
            $table->string('stitch_length')->nullable();
            $table->string('batch_roll')->nullable();
            $table->string('issue_qty')->nullable();
            $table->string('batch_weight')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('sub_dyeing_batch_details');
    }
}
