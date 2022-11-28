<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGarmentsItemIdToV3TrimsStoreStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('v3_trims_store_stock_summaries', function (Blueprint $table) {
            $table->string('garments_item_id')->nullable()->change();
            $table->string('garments_item_name')->nullable()->after('garments_item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_trims_store_stock_summaries', function (Blueprint $table) {
            $table->unsignedBigInteger('garments_item_id')->nullable()->change();
            $table->dropColumn('garments_item_name');
        });
    }
}
