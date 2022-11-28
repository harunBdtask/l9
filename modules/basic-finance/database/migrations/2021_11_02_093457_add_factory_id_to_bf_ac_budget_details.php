<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactoryIdToBfAcBudgetDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_ac_budget_details', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->after('bf_account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_ac_budget_details', function (Blueprint $table) {
            $table->dropColumn('factory_id');
        });
    }
}
