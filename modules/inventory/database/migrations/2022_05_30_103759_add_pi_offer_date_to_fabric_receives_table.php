<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPiOfferDateToFabricReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_receives', function (Blueprint $table) {
            $table->string('pi_offer_date')->nullable()->after('lc_sc_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_receives', function (Blueprint $table) {
            $table->dropColumn('pi_offer_date');
        });
    }
}