<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErpPackingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_packing_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->string('style_name');
            $table->string('po_no');
            $table->string('hanger_name')->nullable();
            $table->string('vessel_name')->nullable();
            $table->string('shipping_mark')->nullable();
            $table->string('port_of_landing')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('erp_packing_lists');
    }
}
