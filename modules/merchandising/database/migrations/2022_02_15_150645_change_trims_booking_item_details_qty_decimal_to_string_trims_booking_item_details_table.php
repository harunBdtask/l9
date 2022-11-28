<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTrimsBookingItemDetailsQtyDecimalToStringTrimsBookingItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_booking_item_details', function (Blueprint $table) {
            $table->string('qty')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_booking_item_details', function (Blueprint $table) {
            $table->decimal('qty', 10, 4)->change();
        });
    }
}
