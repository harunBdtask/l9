<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDyeingGoodsDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyeing_goods_delivery_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dyeing_goods_delivery_id');
            $table->unsignedInteger('dyeing_batch_id')->nullable();
            $table->string('dyeing_batch_no')->nullable();
            $table->unsignedInteger('dyeing_batch_details_id')->nullable();
            $table->unsignedInteger('textile_order_id')->nullable();
            $table->string('textile_order_no')->nullable();
            $table->unsignedInteger('textile_order_details_id')->nullable();
            $table->date('delivery_date')->nullable();
            $table->unsignedInteger('fabric_composition_id')->nullable();
            $table->unsignedInteger('fabric_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->unsignedInteger('dia_type_id')->nullable();
            $table->string('gsm')->nullable();
            $table->text('fabric_description')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('color_type_id')->nullable();
            $table->string('req_order_qty')->nullable();
            $table->string('total_roll')->nullable();
            $table->string('reject_roll')->nullable();
            $table->string('reject_qty')->nullable();
            $table->string('delivery_qty')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('dyeing_goods_delivery_details');
    }
}