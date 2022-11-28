<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKnitCardIdInKnittingRollDeliveryChallanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knitting_roll_delivery_challan_details', function (Blueprint $table) {
            $table->unsignedInteger('knit_card_id')->nullable();
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
            $table->dropColumn('knit_card_id');
        });
    }
}
