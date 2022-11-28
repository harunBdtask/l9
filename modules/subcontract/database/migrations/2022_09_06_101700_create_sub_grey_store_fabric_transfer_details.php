<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGreyStoreFabricTransferDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grey_store_fabric_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_detail_uid')->nullable();
            $table->string('fabric_transfer_id');
            $table->string('criteria');
            $table->string('transfer_date');
            $table->string('transfer_type');
            $table->string('from_store_id');
            $table->string('from_order_id');
            $table->string('from_order_detail_id');
            $table->string('from_remarks')->nullable();
            $table->string('to_store_id');
            $table->string('to_order_id');
            $table->string('to_order_detail_id');
            $table->string('to_remarks')->nullable();
            $table->string('transfer_qty')->nullable();
            $table->string('rate')->nullable();
            $table->string('amount')->nullable();
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
        Schema::dropIfExists('sub_grey_store_fabric_transfer_details');
    }
}
