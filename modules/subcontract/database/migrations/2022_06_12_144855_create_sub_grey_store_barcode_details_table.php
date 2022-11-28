<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGreyStoreBarcodeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grey_store_barcode_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('sub_grey_store_receive_id');
            $table->unsignedInteger('sub_grey_store_receive_detail_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('sub_textile_order_id')->nullable();
            $table->unsignedInteger('sub_textile_order_detail_id')->nullable();
            $table->unsignedInteger('sub_grey_store_id')->nullable();
            $table->string('barcode_qty')->nullable();
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
        Schema::dropIfExists('sub_grey_store_barcode_details');
    }
}
