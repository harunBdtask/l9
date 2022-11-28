<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsInKnittingRollDeliveryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knitting_roll_delivery_challans', function (Blueprint $table) {
            $table->string('destination')->after('challan_date')->nullable();
            $table->string('driver_name')->after('destination')->nullable();
            $table->string('vehicle_no')->after('driver_name')->nullable();
            $table->text('remarks')->after('vehicle_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knitting_roll_delivery_challans', function (Blueprint $table) {
            $table->dropColumn('destination');
            $table->dropColumn('driver_name');
            $table->dropColumn('vehicle_no');
            $table->dropColumn('remarks');
        });
    }
}
