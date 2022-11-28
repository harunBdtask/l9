<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameConversationRateToConversionRateToBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->dropColumn('conversation_rate');
            $table->float('conversion_rate', 8,2)->after('ledger_account_id')->nullable();
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
            $table->string('conversation_rate')->after('ledger_account_id')->nullable();
            $table->dropColumn('conversion_rate');
        });
    }
}
