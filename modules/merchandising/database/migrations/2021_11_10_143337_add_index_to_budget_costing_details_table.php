<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToBudgetCostingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_costing_details', function (Blueprint $table) {
            $table->index('budget_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_costing_details', function (Blueprint $table) {
            $table->dropIndex(['budget_id']);
            $table->dropIndex(['type']);
        });
    }
}
