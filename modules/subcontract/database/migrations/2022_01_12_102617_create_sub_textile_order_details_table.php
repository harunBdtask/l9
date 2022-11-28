<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubTextileOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_textile_order_details', function (Blueprint $table) {
            $table->id();
            $table->text('uuid');
            $table->unsignedBigInteger('sub_textile_order_id');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('order_no');
            $table->unsignedBigInteger('sub_textile_operation_id')->nullable();
            $table->unsignedBigInteger('sub_textile_process_id')->nullable();
            $table->text('operation_description')->nullable();
            $table->unsignedBigInteger('body_part_id')->nullable();
            $table->unsignedBigInteger('fabric_composition_id')->nullable()->comment("new_fabric_compositions table id");
            $table->unsignedBigInteger('fabric_type_id')->nullable()->comment("composition_types table id");
            $table->unsignedBigInteger('color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->unsignedBigInteger('color_type_id')->nullable();
            $table->string('finish_dia', 40)->nullable();
            $table->tinyInteger('dia_type_id')->nullable()->comment("1=Open,2=Tubular,3=Needle Open");
            $table->string('gsm', 40)->nullable();
            $table->text('fabric_description')->nullable()->comment("fabric_composition + fabric_type +color + ld_no + color_type + finish_dia + dia_type + gsm");
            $table->json('yarn_details')->nullable();
            $table->string('customer_buyer')->nullable();
            $table->string('customer_style')->nullable();
            $table->string('order_qty')->nullable();
            $table->unsignedBigInteger('unit_of_measurement_id')->nullable();
            $table->string('price_rate')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('total_value')->nullable();
            $table->string('conv_rate')->nullable();
            $table->string('total_amount_bdt')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('sub_textile_order_details');
    }
}
