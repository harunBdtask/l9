<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseColumnsSizeToTrimsBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_booking_details', function (Blueprint $table) {
            $table->string('style_name', 190)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_booking_details', function (Blueprint $table) {
            $table->string('style_name', 30)->change();
        });
    }
}
