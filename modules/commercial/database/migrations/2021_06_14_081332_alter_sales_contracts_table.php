<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSalesContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_contracts', function (Blueprint $table) {
            $table->dropColumn('currency');
            $table->string('bank_file_no')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
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
            $table->string('currency', 10)->nullable();
            $table->dropColumn('currency_id');
            $table->dropColumn('bank_file_no');
        });
    }
}
