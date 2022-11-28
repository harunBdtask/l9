<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingIdAndBookingNoToV3TrimsStoreReceiveReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('v3_trims_store_receive_return_details', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->nullable()->after('pi_numbers');
            $table->string('booking_no')->nullable()->after('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_trims_store_receive_return_details', function (Blueprint $table) {
            $table->dropColumn('booking_id');
            $table->dropColumn('booking_no');
        });
    }
}
