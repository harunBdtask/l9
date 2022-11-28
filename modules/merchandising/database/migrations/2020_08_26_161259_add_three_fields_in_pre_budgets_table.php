<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThreeFieldsInPreBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_budgets', function (Blueprint $table) {
//            $table->unsignedInteger('number_of_machines')->nullable();
//            $table->unsignedInteger('production_per_hour')->nullable();
//            $table->unsignedInteger('production_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_budgets', function (Blueprint $table) {
//            $table->dropColumn(['number_of_machines', 'production_per_hour', 'production_days']);
        });
    }
}
