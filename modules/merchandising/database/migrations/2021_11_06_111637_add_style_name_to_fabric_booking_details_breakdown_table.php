<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStyleNameToFabricBookingDetailsBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->string('style_name')->after('fabric_composition_value')->nullable();
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
            $table->dropColumn('style_name');
        });
    }
}
