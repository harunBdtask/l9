<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountingUsersToBfVariableSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_variable_settings', function (Blueprint $table) {
            $table->json('accounting_users')->nullable()->after('voucher_preview_signature');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_variable_settings', function (Blueprint $table) {
            $table->dropColumn('accounting_users');
        });
    }
}
