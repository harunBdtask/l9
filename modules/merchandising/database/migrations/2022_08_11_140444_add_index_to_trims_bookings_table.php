<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToTrimsBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_bookings', function (Blueprint $table) {
            $table->index('unique_id');
            $table->index('booking_date');
            $table->index('delivery_date');
            $table->index('pay_mode');
            $table->index('source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_bookings', function (Blueprint $table) {
            $table->dropIndex(['unique_id', 'booking_date', 'delivery_date', 'pay_mode', 'source']);
        });
    }
}
