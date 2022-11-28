<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBudgetPercentageColumnToCommercialCostMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commercial_cost_methods', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('budget_percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commercial_cost_methods', function (Blueprint $table) {
            $table->dropColumn('budget_percentage');
        });
    }
}
