<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDealingMerchantIdToSampleTrimsBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_trims_booking_details', function (Blueprint $table) {
            $table->unsignedInteger('dealing_merchant_id')->nullable()->after('uom_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_trims_booking_details', function (Blueprint $table) {
            $table->dropColumn('dealing_merchant_id');
        });
    }
}
