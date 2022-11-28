<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeBfAccountIdToBfAcBudgetApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_ac_budget_approvals', function (Blueprint $table) {
            $table->unsignedInteger('bf_account_id')->after('bf_ac_budget_detail_id');
            $table->string('code')->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_ac_budget_approvals', function (Blueprint $table) {
            $table->dropColumn('bf_account_id');
            $table->dropColumn('code');
        });
    }
}
