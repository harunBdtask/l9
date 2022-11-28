<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectIdUnitIdAndAccountTypeIdToBfBankAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_bank_accounts', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->nullable()->after('factory_id');
            $table->unsignedInteger('unit_id')->nullable()->after('project_id');
            $table->unsignedInteger('account_type_id')->nullable()->after('branch_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_bank_accounts', function (Blueprint $table) {
            $table->dropColumn('project_id');
            $table->dropColumn('unit_id');
            $table->dropColumn('account_type_id');
        });
    }
}
