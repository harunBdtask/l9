<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPoNoColumnToEmbellishmentBookingItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('embellishment_booking_item_details', function (Blueprint $table) {
            $table->string('po_no')->nullable()->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('embellishment_booking_item_details', function (Blueprint $table) {
            $table->dropColumn('po_no');
        });
    }
}
