<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddControlLedgerIdToBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->unsignedInteger('control_ledger_id')->nullable()->after('link');
            $table->unsignedInteger('ledger_account_id')->nullable()->after('control_ledger_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->dropColumn('control_ledger_id');
            $table->dropColumn('ledger_account_id');
        });
    }
}
