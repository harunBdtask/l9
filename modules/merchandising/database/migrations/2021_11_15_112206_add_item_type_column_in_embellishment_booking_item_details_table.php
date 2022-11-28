<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemTypeColumnInEmbellishmentBookingItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('embellishment_booking_item_details', function (Blueprint $table) {
            $table->string('item_type_id')->after('item_id')->nullable();
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
            $table->dropColumn('item_type_id');
        });
    }
}
