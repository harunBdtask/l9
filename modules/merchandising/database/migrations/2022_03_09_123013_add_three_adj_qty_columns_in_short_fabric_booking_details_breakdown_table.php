<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThreeAdjQtyColumnsInShortFabricBookingDetailsBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('short_fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->string('first_adj_qty')->after('wo_qty')->nullable()->default(0);
            $table->string('second_adj_qty')->after('first_adj_qty')->nullable()->default(0);
            $table->string('third_adj_qty')->after('second_adj_qty')->nullable()->default(0);
            $table->tinyInteger('adj_qty_status')->after('remarks')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('short_fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->dropColumn('first_adj_qty');
            $table->dropColumn('second_adj_qty');
            $table->dropColumn('third_adj_qty');
            $table->dropColumn('adj_qty_status');
        });
    }
}
