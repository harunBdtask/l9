<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBToBMarginLcDetailsAddPrimaryMasterContractId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b_to_b_margin_lc_details', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_master_contract_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b_to_b_margin_lc_details', function (Blueprint $table) {
            $table->dropColumn('primary_master_contract_id');
        });
    }
}
