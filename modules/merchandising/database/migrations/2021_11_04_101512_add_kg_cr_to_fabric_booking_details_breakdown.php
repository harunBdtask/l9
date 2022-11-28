<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKgCrToFabricBookingDetailsBreakdown extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->double('kg_cr')->nullable()->after('moq_qty');
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
            $table->dropColumn('kg_cr');
        });
    }
}
