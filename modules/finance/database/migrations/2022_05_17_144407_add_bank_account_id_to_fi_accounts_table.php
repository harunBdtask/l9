<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankAccountIdToFiAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fi_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_account_id')->nullable()->after('parent_ac');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fi_accounts', function (Blueprint $table) {
            $table->dropColumn('bank_account_id');
        });
    }
}
