<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueIdFieldToExportAndSalesContractLc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_contracts', function (Blueprint $table) {
            $table->string('unique_id')->after('id');
        });
        Schema::table('export_lc', function (Blueprint $table) {
            $table->string('unique_id')->after('id');
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
            $table->dropColumn('unique_id');
        });

        Schema::table('export_lc', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });
    }
}
