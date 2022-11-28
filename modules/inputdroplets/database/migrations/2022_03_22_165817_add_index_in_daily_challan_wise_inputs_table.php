<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexInDailyChallanWiseInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_challan_wise_inputs', function (Blueprint $table) {
            $table->index(['floor_id', 'line_id']);
            $table->index(['buyer_id', 'order_id']);
            $table->index(['purchase_order_id', 'color_id']);
            $table->index('challan_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_challan_wise_inputs', function (Blueprint $table) {
            $table->dropIndex('daily_challan_wise_inputs_floor_id_line_id_index');
            $table->dropIndex('daily_challan_wise_inputs_buyer_id_order_id_index');
            $table->dropIndex('daily_challan_wise_inputs_purchase_order_id_color_id_index');
            $table->dropIndex('daily_challan_wise_inputs_challan_no_index');
        });
    }
}
