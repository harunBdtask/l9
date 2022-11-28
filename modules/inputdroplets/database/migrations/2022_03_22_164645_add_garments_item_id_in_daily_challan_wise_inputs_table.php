<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGarmentsItemIdInDailyChallanWiseInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_challan_wise_inputs', function (Blueprint $table) {
            $table->unsignedBigInteger('garments_item_id')->after('purchase_order_id')->nullable()->index();
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
            $table->dropColumn('garments_item_id');
        });
    }
}
