<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToPoItemColorSizeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('po_item_color_size_details', function (Blueprint $table) {
            $table->index('factory_id');
            $table->index('order_id');
            $table->index('purchase_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('po_item_color_size_details', function (Blueprint $table) {
            $table->dropIndex(['factory_id']);
            $table->dropIndex(['order_id']);
            $table->dropIndex(['purchase_order_id']);
        });
    }
}
