<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePnPackingListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pn_packing_list', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('production_date');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('size_id');
            $table->string('size_wise_qty');
            $table->string('destination');
            $table->boolean('tag_type');
            $table->string('no_of_carton');
            $table->string('qty_per_carton');
            $table->string('no_of_boxes');
            $table->string('blister_kit_carton');
            $table->string('kit_bc_carton');
            $table->string('carton_no_from');
            $table->string('carton_no_to');
            $table->string('measurement_l');
            $table->string('measurement_w');
            $table->string('measurement_h');
            $table->string('bc_height');
            $table->string('gw_box_weight');
            $table->string('bc_gw');
            $table->string('nw_box_weight');
            $table->string('bc_nw');
            $table->string('m3_cbu');
            $table->string('type_of_shipment');
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
        Schema::dropIfExists('pn_packing_list');
    }
}
