<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransferAndTransferMetaTableTrimsStockSummaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_stock_summaries', function (Blueprint $table) {
            $table->string('transfer', 20)->default('0')->after('receive_amount');
            $table->json('transfer_meta')->nullable()->after('meta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_stock_summaries', function (Blueprint $table) {
            $table->dropColumn('transfer');
            $table->dropColumn('transfer_meta');
        });
    }
}