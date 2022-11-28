<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGmtColorColumnToFabricBookingDetailsBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->string('gmt_color')->nullable()->after('item_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->dropColumn('gmt_color');
        });
    }
}
