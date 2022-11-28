<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFactoryIdStoreIdToFabricStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_stock_summaries', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->after('id');
            $table->unsignedInteger('store_id')->nullable()->after('ac_gsm');
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
            $table->dropColumn('factory_id');
            $table->dropColumn('store_id');
        });
    }
}
