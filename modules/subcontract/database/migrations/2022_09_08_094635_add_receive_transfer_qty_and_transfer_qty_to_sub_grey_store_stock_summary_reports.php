<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiveTransferQtyAndTransferQtyToSubGreyStoreStockSummaryReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_grey_store_stock_summary_reports', function (Blueprint $table) {
            $table->string('receive_transfer_qty')->nullable()->default(0)->after('issue_return_qty');
            $table->string('transfer_qty')->nullable()->default(0)->after('receive_transfer_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_grey_store_stock_summary_reports', function (Blueprint $table) {
            $table->dropColumn([
                'receive_transfer_qty',
                'transfer_qty'
            ]);
        });
    }
}
