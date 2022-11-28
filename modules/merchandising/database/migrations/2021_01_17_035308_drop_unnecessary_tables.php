<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropUnnecessaryTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('order_color_breakdown');
        Schema::dropIfExists('order_item_details');
        Schema::dropIfExists('shipment_related_date_histories');
        Schema::dropIfExists('order_size_breakdown');
        Schema::dropIfExists('purchase_order_details');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
