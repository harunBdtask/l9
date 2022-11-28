<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinishFabDiaInBudgetYeanDetails extends Migration
{
    public function up()
    {
        Schema::table('budget_yarn_components', function (Blueprint $table) {
            $table->float('finish_fab_dia');
        });
    }

    public function down()
    {
        // Schema::table('budget_yarn_components', function (Blueprint $table) {
        //     $table->dropColumn('finish_fab_dia');
        // });
    }
}
