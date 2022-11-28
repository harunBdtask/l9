<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErpPackingListDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_packing_list_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('erp_packing_list_id');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('color_id');
            $table->string('customer_name')->nullable();
            $table->string('ctn_no_from')->nullable();
            $table->string('ctn_no_to')->nullable();
            $table->string('ctn_qty')->nullable();
            $table->string('order_qty')->nullable();
            $table->string('team_or_color')->nullable();
            $table->string('qty_pcs_per_ctn')->nullable();
            $table->string('ttl_qty_in_pcs')->nullable();
            $table->string('net_weight')->nullable();
            $table->string('grs_weight')->nullable();
            $table->string('total_net_weight')->nullable();
            $table->string('total_grs_weight')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('cbm')->nullable();
            $table->json('size_ratio')->nullable();
            $table->json('sizes')->nullable();
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
        Schema::dropIfExists('erp_packing_list_details');
    }
}
