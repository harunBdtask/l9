<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTotalAmountToBfAcBudgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_ac_budgets', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->default(0.00)->after('code')->change();
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
            $table->decimal('total_amount', 15, 4)->default(0.0000)->after('code')->change();
        });
    }
}
