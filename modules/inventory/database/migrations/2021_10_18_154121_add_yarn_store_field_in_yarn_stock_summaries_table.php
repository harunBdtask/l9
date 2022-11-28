<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYarnStoreFieldInYarnStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_stock_summaries', function (Blueprint $table) {
            $table->unsignedInteger('store_id')->after('uom_id');
        });

        Schema::table('yarn_date_wise_stock_summaries', function (Blueprint $table) {
            $table->unsignedInteger('store_id')->after('uom_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_stock_summaries', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });

        Schema::table('yarn_date_wise_stock_summaries', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });
    }
}
