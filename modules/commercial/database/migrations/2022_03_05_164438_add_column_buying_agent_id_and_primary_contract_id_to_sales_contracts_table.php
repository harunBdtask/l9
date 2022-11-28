<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBuyingAgentIdAndPrimaryContractIdToSalesContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('sales_contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('buying_agent_id')->nullable()->after('b_to_b_margin_lc_id');
        });
        Schema::table('sales_contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_contract_id')->nullable()->after('buying_agent_id');
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
            $table->dropColumn('buying_agent_id');
        });
        Schema::table('sales_contracts', function (Blueprint $table) {
            $table->dropColumn('primary_contract_id');
        });
    }
}
