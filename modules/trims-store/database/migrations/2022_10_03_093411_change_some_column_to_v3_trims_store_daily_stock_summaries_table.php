<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSomeColumnToV3TrimsStoreDailyStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('v3_trims_store_daily_stock_summaries', function (Blueprint $table) {
            $table->string('receive_qty')->nullable()->default(0)->change();
            $table->string('receive_reject_qty')->nullable()->default(0)->change();
            $table->string('receive_return_qty')->nullable()->default(0)->change();
            $table->string('issue_qty')->nullable()->default(0)->change();
            $table->string('issue_reject_qty')->nullable()->default(0)->change();
            $table->string('issue_return_qty')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_trims_store_daily_stock_summaries', function (Blueprint $table) {
            $table->string('receive_qty')->nullable()->change();
            $table->string('receive_reject_qty')->nullable()->change();
            $table->string('receive_return_qty')->nullable()->change();
            $table->string('issue_qty')->nullable()->change();
            $table->string('issue_reject_qty')->nullable()->change();
            $table->string('issue_return_qty')->nullable()->change();
        });
    }
}
