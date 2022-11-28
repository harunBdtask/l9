<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePrecisionTrimsBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_booking_details', function (Blueprint $table) {
            $table->decimal('balance_qty', 10, 4)->default(0)->change();
            $table->decimal('work_order_qty', 10, 4)->default(0)->change();
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
            $table->decimal('balance_qty')->default(0)->change();
            $table->decimal('work_order_qty')->default(0)->change();
        });
    }
}
