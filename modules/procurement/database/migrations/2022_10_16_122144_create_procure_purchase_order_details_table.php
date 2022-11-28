<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcurePurchaseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procure_purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('quotation_id');
            $table->integer('qty');
            $table->float('unit_price', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procure_purchase_order_details');
    }
}
