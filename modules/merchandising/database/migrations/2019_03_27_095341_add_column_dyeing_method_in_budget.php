<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDyeingMethodInBudget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_dyeing_components', function (Blueprint $table) {
            $table->string('dyeing_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_dyeing_components', function (Blueprint $table) {
            // $table->dropColumn('dyeing_method');
        });
    }
}
