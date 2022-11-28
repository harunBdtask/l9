<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactoryIdToBfAcBudgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_ac_budgets', function (Blueprint $table) {
            $table->unsignedInteger('factory_id')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_ac_budgets', function (Blueprint $table) {
            $table->dropColumn('factory_id');
        });
    }
}
