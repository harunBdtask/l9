<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSampleTrimsBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_trims_booking_details', function (Blueprint $table) {
            $table->unsignedInteger('item_id')->after('sample_trims_booking_id');
            $table->unsignedInteger('uom_id')->after('item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_trims_booking_details', function (Blueprint $table) {
            $table->dropColumn('item_id');
            $table->dropColumn('uom_id');
        });
    }
}
