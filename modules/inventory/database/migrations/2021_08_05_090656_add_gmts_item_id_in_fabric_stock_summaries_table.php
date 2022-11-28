<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGmtsItemIdInFabricStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_stock_summaries', function (Blueprint $table) {
            $table->unsignedInteger('gmts_item_id');

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
            $table->dropColumn('gmts_item_id');
        });
    }
}
