<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPreBudgetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_budget_details', function (Blueprint $table) {
//            $table->double('percentage')->nullable();
//            $table->double('cm_total')->nullable();
//            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_budget_details', function (Blueprint $table) {
//            $table->dropColumn([
//                'percentage',
//                'cm_total',
//                'remarks'
//            ]);
        });
    }
}
