<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnToFiBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fi_bank_accounts', function (Blueprint $table) {
            $table->tinyInteger('currency_type_id')->nullable()->after('ledger_account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fi_bank_accounts', function (Blueprint $table) {
            $table->dropColumn('currency_type_id');
        });
    }
}
