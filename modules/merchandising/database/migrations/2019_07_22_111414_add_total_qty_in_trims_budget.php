<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalQtyInTrimsBudget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
//            $table->float('total_qty');
//            $table->float('consumption_qty');
//        });
//        Schema::table('budget_masters', function (Blueprint $table) {
//            $table->string('budget_number');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
//            $table->dropColumn('total_qty');
//        });
    }
}
