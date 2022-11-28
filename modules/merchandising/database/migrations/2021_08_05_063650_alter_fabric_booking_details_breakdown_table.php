<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFabricBookingDetailsBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->unsignedBigInteger('garments_item_id')->nullable()->after('body_part_id');
            $table->string('garments_item_name')->nullable()->after('garments_item_id');
            $table->json('breakdown')->nullable()->after('garments_item_name');
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
            $table->dropColumn('garments_item_id');
            $table->dropColumn('garments_item_name');
            $table->dropColumn('breakdown');
        });
    }
}
