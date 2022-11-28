<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRollQtyColumnsToSubGreyStoreDailyStockSummaryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_grey_store_daily_stock_summary_reports', function (Blueprint $table) {
            $table->string('total_issue_roll')->nullable()->after('issue_return_qty');
            $table->string('return_issue_roll')->nullable()->after('total_issue_roll');
            $table->string('total_receive_roll')->nullable()->after('return_issue_roll');
            $table->string('return_receive_roll')->nullable()->after('total_receive_roll');
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
            $table->dropColumn(['total_issue_roll', 'return_issue_roll', 'total_receive_roll', 'return_receive_roll']);
        });
    }
}
