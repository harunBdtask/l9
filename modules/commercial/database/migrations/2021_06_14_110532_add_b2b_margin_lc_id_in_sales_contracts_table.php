<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddB2bMarginLcIdInSalesContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('b_to_b_margin_lc_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_contracts', function (Blueprint $table) {
            $table->dropColumn('b_to_b_margin_lc_id');
        });
    }
}
