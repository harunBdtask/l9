<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactoryIdInYarnDateWiseStockSummaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_date_wise_stock_summaries', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->after('id')->nullable();
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
            $table->dropColumn('factory_id');
        });
    }
}
