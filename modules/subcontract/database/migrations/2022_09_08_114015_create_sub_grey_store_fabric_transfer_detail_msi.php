<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGreyStoreFabricTransferDetailMSI extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grey_store_fabric_transfer_detail_msi', function (Blueprint $table) {
            $table->id();
            $table->string('fabric_transfer_id');
            $table->string('transfer_detail_id');
            $table->date('transfer_date');

            $table->string('form_operation_id')->nullable();
            $table->string('form_body_part_id')->nullable();
            $table->string('form_fabric_composition_id')->nullable();
            $table->string('form_fabric_type_id')->nullable();
            $table->string('form_color_id')->nullable();
            $table->string('form_ld_no')->nullable();
            $table->string('form_color_type_id')->nullable();
            $table->string('form_finish_dia')->nullable();
            $table->string('form_dia_type_id')->nullable();
            $table->string('form_gsm')->nullable();
            $table->string('form_unit_of_measurement_id')->nullable();
            $table->string('form_fabric_description')->nullable();
            $table->string('form_total_roll')->nullable();

            $table->string('to_operation_id')->nullable();
            $table->string('to_body_part_id')->nullable();
            $table->string('to_fabric_composition_id')->nullable();
            $table->string('to_fabric_type_id')->nullable();
            $table->string('to_color_id')->nullable();
            $table->string('to_color_type_id')->nullable();
            $table->string('to_ld_no')->nullable();
            $table->string('to_finish_dia')->nullable();
            $table->string('to_dia_type_id')->nullable();
            $table->string('to_gsm')->nullable();
            $table->string('to_fabric_description')->nullable();
            $table->string('to_unit_of_measurement_id')->nullable();
            $table->string('to_total_roll')->nullable();

            $table->string('transfer_qty')->nullable();
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
        Schema::dropIfExists('sub_grey_store_fabric_transfer_detail_msi');
    }
}
