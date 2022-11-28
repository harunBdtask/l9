<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlannedGarmentsQtyToTrimsStoreBinCardDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_store_bin_card_details', function (Blueprint $table) {
            $table->string('planned_garments_qty')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_store_bin_card_details', function (Blueprint $table) {
            $table->dropColumn('planned_garments_qty');
        });
    }
}