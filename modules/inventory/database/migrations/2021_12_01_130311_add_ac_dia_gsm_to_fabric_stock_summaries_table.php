<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcDiaGsmToFabricStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_stock_summaries', function (Blueprint $table) {
            $table->string('ac_dia', 10)->nullable()->after('dia');
            $table->string('ac_gsm', 10)->nullable()->after('gsm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_stock_summaries', function (Blueprint $table) {
            $table->dropColumn('ac_dia');
            $table->dropColumn('ac_gsm');
        });
    }
}
