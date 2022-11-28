<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoqQtyColumnToFabricBookingDetailsBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->string('moq_qty')->after('adj_qty')->nullable();
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
            $table->dropColumn('moq_qty');
        });
    }
}