<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSomeFieldNullableInBudgetTrimsAndAccessories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
//            $table->string('measurement')->nullable()->change();
//            $table->text('item_desc')->nullable()->change();
//
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
//            // $table->string('measurement')->change();
//            // $table->text('item_desc')->change();
//
//        });
    }
}
