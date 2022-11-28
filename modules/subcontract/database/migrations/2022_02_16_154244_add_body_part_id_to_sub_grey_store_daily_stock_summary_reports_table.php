<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBodyPartIdToSubGreyStoreDailyStockSummaryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_grey_store_daily_stock_summary_reports', function (Blueprint $table) {
            $table->unsignedInteger('body_part_id')->nullable()
                ->after('sub_textile_operation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_grey_store_daily_stock_summary_reports', function (Blueprint $table) {
            $table->dropColumn('body_part_id');
        });
    }
}
