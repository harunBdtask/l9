<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBatchNoToFabricDateWiseStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_date_wise_stock_summaries', function (Blueprint $table) {
            $table->string('batch_no', 60)->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_date_wise_stock_summaries', function (Blueprint $table) {
            $table->dropColumn('batch_no');
        });
    }
}
