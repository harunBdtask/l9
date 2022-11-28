<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrimsBookingIdAndTrimsBookingDetailIdToTrimsInventoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_inventory_details', function (Blueprint $table) {
            $table->unsignedBigInteger('trims_booking_id')->after('id');
            $table->unsignedBigInteger('trims_booking_detail_id')->after('trims_booking_id');
            $table->string('sensitivity')->nullable()->after('receive_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_inventory_details', function (Blueprint $table) {
            $table->dropColumn('trims_booking_id');
            $table->dropColumn('trims_booking_detail_id');
            $table->dropColumn('sensitivity');
        });
    }
}
