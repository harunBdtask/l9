<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAmountToBfAcBudgetDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_ac_budget_details', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->default(0.00)->change();
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
            $table->decimal('amount', 15, 4)->default(0.00)->change();
        });
    }
}
