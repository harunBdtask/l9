<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeAmountColumnsInYarnStockSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_stock_summaries', function (Blueprint $table) {
            $table->string('receive_return_amount', 20)->after('receive_return_qty')->nullable();
            $table->string('issue_amount', 20)->after('issue_qty')->nullable();
            $table->string('issue_return_amount', 20)->after('issue_return_qty')->nullable();
            $table->string('yarn_brand')->after('yarn_color')->nullable();
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
            $table->dropColumn('receive_return_amount');
            $table->dropColumn('issue_amount');
            $table->dropColumn('issue_return_amount');
            $table->dropColumn('yarn_brand');
        });
    }
}
