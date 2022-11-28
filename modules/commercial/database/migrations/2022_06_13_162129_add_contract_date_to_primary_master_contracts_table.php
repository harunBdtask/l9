<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractDateToPrimaryMasterContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('primary_master_contracts', function (Blueprint $table) {
            $table->date('contract_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('primary_master_contracts', function (Blueprint $table) {
            $table->dropColumn('contract_date');
        });
    }
}
