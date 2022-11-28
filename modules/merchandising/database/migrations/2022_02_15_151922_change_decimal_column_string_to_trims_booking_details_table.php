<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDecimalColumnStringToTrimsBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_booking_details', function (Blueprint $table) {
            $table->string('total_qty')->change();
            $table->string('current_work_order_qty')->change();
            $table->string('total_amount')->change();
            $table->string('balance_amount')->change();
            $table->string('balance_qty')->change();
            $table->string('work_order_qty')->change();
            $table->string('work_order_rate')->change();
            $table->string('work_order_amount')->change();
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
            $table->decimal('total_qty', 10, 4)->change();
            $table->decimal('current_work_order_qty', 10, 4)->change();
            $table->decimal('total_amount', 10, 4)->change();
            $table->decimal('balance_amount', 10, 4)->change();
            $table->decimal('balance_qty', 10, 4)->change();
            $table->decimal('work_order_qty', 10, 4)->change();
            $table->decimal('work_order_rate', 10, 4)->change();
            $table->decimal('work_order_amount', 10, 4)->change();
        });
    }
}
