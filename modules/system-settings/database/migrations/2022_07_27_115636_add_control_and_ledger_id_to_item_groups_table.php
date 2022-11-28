<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddControlAndLedgerIdToItemGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('control_ledger')->nullable();
            $table->unsignedBigInteger('ledger_ac')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_groups', function (Blueprint $table) {
            $table->dropColumn('control_ledger');
            $table->dropColumn('ledger_ac');
        });
    }
}
