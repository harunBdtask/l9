<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransferQtyAndTransferredFromColumnInYarnDateWiseStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_date_wise_stock_summaries', function (Blueprint $table) {
            $table->string('transfer_qty', 20)->default('0');
            $table->unsignedInteger('transferred_from')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_date_wise_stock_summaries', function (Blueprint $table) {
            $table->dropColumn(['transfer_qty', 'transferred_from']);
        });
    }
}
