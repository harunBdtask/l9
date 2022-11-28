<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubLedgerIdToAccountSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_suppliers', function (Blueprint $table) {
            $table->unsignedInteger('sub_ledger_account_id')->nullable()->after('ledger_account_id');
            $table->dropColumn('ledger_account_name');
            $table->dropColumn('sub_ledger_account_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_suppliers', function (Blueprint $table) {
            $table->dropColumn('sub_ledger_account_id');
            $table->string('ledger_account_name')->nullable()->after('name');
            $table->string('sub_ledger_account_name')->nullable()->after('ledger_account_name');
        });
    }
}
