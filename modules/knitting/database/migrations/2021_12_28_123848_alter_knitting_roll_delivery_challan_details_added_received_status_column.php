<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterKnittingRollDeliveryChallanDetailsAddedReceivedStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knitting_roll_delivery_challan_details', function (Blueprint $table) {
            $table->integer('received_status')->nullable()->after('factory_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knitting_roll_delivery_challan_details', function (Blueprint $table) {
            $table->dropColumn('received_status');
        });
    }
}
