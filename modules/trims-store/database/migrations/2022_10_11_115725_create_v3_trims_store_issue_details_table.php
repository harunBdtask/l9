<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateV3TrimsStoreIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v3_trims_store_issue_details', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('trims_store_issue_id');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('style_id');
            $table->string('po_numbers')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->string('booking_no')->nullable();
            $table->unsignedBigInteger('garments_item_id')->nullable();
            $table->string('item_code')->nullable();
            $table->unsignedBigInteger('item_id');
            $table->tinyInteger('sensitivity_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('item_description')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->string('order_qty')->nullable();
            $table->string('wo_qty')->nullable();
            $table->string('issue_qty')->nullable();
            $table->unsignedBigInteger('uom_id');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('rate');
            $table->string('exchange_rate')->nullable();
            $table->string('amount');
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('rack_id')->nullable();
            $table->unsignedBigInteger('shelf_id')->nullable();
            $table->unsignedBigInteger('bin_id')->nullable();
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
        Schema::dropIfExists('v3_trims_store_issue_details');
    }
}
