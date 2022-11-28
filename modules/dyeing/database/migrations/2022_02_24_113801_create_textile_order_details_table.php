<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextileOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('textile_order_details', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedInteger('textile_order_id');
            $table->unsignedInteger('fabric_sales_order_detail_id');
            $table->unsignedInteger('sub_textile_operation_id');
            $table->unsignedInteger('sub_textile_process_id');
            $table->string('operation_description')->nullable();
            $table->unsignedInteger('body_part_id')->nullable();
            $table->unsignedInteger('fabric_composition_id')->nullable();
            $table->unsignedInteger('fabric_type_id')->nullable();
            $table->unsignedInteger('item_color_id')->nullable();
            $table->unsignedInteger('gmt_color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->unsignedInteger('color_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->unsignedInteger('dia_type_id')->nullable();
            $table->string('gsm')->nullable();
            $table->json('yarn_details')->nullable();
            $table->string('customer_buyer')->nullable();
            $table->string('customer_style')->nullable();
            $table->string('order_qty')->nullable();
            $table->unsignedInteger('uom_id')->nullable();
            $table->string('price_rate')->nullable();
            $table->string('total_value')->nullable();
            $table->string('conv_rate')->nullable();
            $table->string('total_amount_bdt')->nullable();
            $table->date('delivery_date')->nullable();
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
        Schema::dropIfExists('textile_order_details');
    }
}
