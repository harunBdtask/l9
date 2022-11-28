<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlannedGarmentsQtyToTrimsStoreDeliveryChallanDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_store_delivery_challan_details', function (Blueprint $table) {
            $table->string('planned_garments_qty')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_store_delivery_challan_details', function (Blueprint $table) {
            $table->dropColumn('planned_garments_qty');
        });
    }
}
