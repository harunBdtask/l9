<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyColumnDataTypeInTrimsAndAccessories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        DB::statement('ALTER TABLE budget_trims_accessories_components CHANGE  con_qty  con_qty DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_trims_accessories_components CHANGE  excess_qty  excess_qty DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_trims_accessories_components CHANGE  total_cost  total_cost DOUBLE(10,3)');
//        DB::statement('ALTER TABLE budget_trims_accessories_components CHANGE  cost_per_unit  cost_per_unit DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_trims_accessories_components CHANGE  required_qty  required_qty DOUBLE(9,3)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
//            // $table->integer('con_qty')->change();
//            // $table->integer('required_qty')->change();
//            $table->float('cost_per_unit')->change();
//            $table->float('total_cost')->change();
//            $table->unsignedInteger('excess_qty')->after('required_qty')->change();
//        });
    }
}
