<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarksControlBudgetMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_masters', function (Blueprint $table) {
//            $table->string('control')->nullable();
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
        Schema::table('budget_masters', function (Blueprint $table) {
//            $table->dropColumn('control');
//            $table->dropColumn('remarks');
        });
    }
}
