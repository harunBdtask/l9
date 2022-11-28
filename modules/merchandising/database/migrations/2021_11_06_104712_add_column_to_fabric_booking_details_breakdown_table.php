<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToFabricBookingDetailsBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->string('fabric_composition_id')->after('garments_item_name')->nullable();
            $table->string('fabric_composition_value')->after('fabric_composition_id')->nullable();
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
            $table->dropColumn('fabric_composition_id');
            $table->dropColumn('fabric_composition_value');
        });
    }
}
