<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterSomeDefaultFloatPointToCustomFloatPoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        DB::statement('ALTER TABLE budget_direct_fabric_components CHANGE  fabric_required_qty  fabric_required_qty DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_direct_fabric_components CHANGE  unit_price  unit_price DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_direct_fabric_components CHANGE  total_amount  total_amount DOUBLE(10,3)');
//        DB::statement('ALTER TABLE budget_direct_fabric_components CHANGE  required_dia  required_dia DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_direct_fabric_components CHANGE  fabric_consumption_qty  fabric_consumption_qty DOUBLE(9,3)');
//
//        DB::statement('ALTER TABLE budget_dyeing_components CHANGE  dyeing_fab_req_qty  dyeing_fab_req_qty DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_dyeing_components CHANGE  price_per_kg  price_per_kg DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_dyeing_components CHANGE  dyeing_total  dyeing_total DOUBLE(10,3)');
//
//        DB::statement('ALTER TABLE budget_yarn_components CHANGE  price_per_kg price_per_kg DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_yarn_components CHANGE  total total DOUBLE(10,3)');
//        DB::statement('ALTER TABLE budget_yarn_components CHANGE  fabric_req_qty fabric_req_qty DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_yarn_components CHANGE  finish_fab_dia finish_fab_dia DOUBLE(9,3)');
//
//        DB::statement('ALTER TABLE budget_knitting_components CHANGE  knitting_fabric_req_qty knitting_fabric_req_qty DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_knitting_components CHANGE  knitting_fabric_price_per_kg knitting_fabric_price_per_kg DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_knitting_components CHANGE  knitting_total knitting_total DOUBLE(10,3)');
//
//        DB::statement('ALTER TABLE budget_gray_fabric_components CHANGE  gray_required_dia gray_required_dia DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_gray_fabric_components CHANGE  gray_fabric_required_qty gray_fabric_required_qty DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_gray_fabric_components CHANGE  gray_unit_price gray_unit_price DOUBLE(9,3)');
//        DB::statement('ALTER TABLE budget_gray_fabric_components CHANGE  gray_total_amount gray_total_amount DOUBLE (10,3)');
//        DB::statement('ALTER TABLE budget_gray_fabric_components CHANGE  gray_fabric_consumption_qty gray_fabric_consumption_qty DOUBLE(9,3)');
//
//        //        Schema::table('budget_direct_fabric_components', function (Blueprint $table) {
//        //            $table->float('fabric_required_qty',8,3)->change();
//        //            $table->float('unit_price',8,3)->change();
//        //            $table->float('total_amount',8,3)->change();
//        //            $table->float('required_dia',8,3)->change();
//        //            $table->float('fabric_consumption_qty',8,3)->change();
//        //        });
//        //        Schema::table('budget_dyeing_components', function (Blueprint $table) {
//        //            $table->float('dyeing_fab_req_qty',8,3)->change();
//        //            $table->float('price_per_kg',8,3)->change();
//        //            $table->float('dyeing_total',8,3)->change();
//        //        });
//
//        //        Schema::table('budget_yarn_components', function (Blueprint $table) {
//        //            $table->float('yarn_req_qty',8,3)->change();
//        //            $table->float('price_per_kg',8,3)->change();
//        //            $table->float('total',8,3)->change();
//        //            $table->float('fabric_req_qty',8,3)->change();
//        //            $table->float('finish_fab_dia',8,3)->change();
//        //        });
//
//        //        Schema::table('budget_knitting_components', function (Blueprint $table) {
//        //            $table->float('knitting_fabric_req_qty',8,3)->change();
//        //            $table->float('knitting_fabric_price_per_kg',8,3)->change();
//        //            $table->float('knitting_total',8,3)->change();
//        //        });
//
//        //        Schema::table('budget_gray_fabric_components', function (Blueprint $table) {
//        //            $table->float('gray_required_dia',8,3)->change();
//        //            $table->float('gray_fabric_required_qty',8,3)->change();
//        //            $table->float('gray_unit_price',8,3)->change();
//        //            $table->float('gray_total_amount',8,3)->change();
//        //            $table->float('gray_fabric_consumption_qty',8,3)->change();
//        //
//        //        });
//
//
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('budget_direct_fabric_components', function (Blueprint $table) {
//            $table->float('fabric_required_qty')->change();
//            $table->float('unit_price')->change();
//            $table->float('total_amount')->change();
//            $table->unsignedInteger('required_dia')->change();
//            $table->float('fabric_consumption_qty')->change();
//        });
//        Schema::table('budget_gray_fabric_components', function (Blueprint $table) {
//            $table->float('gray_required_dia')->change();
//            $table->float('gray_fabric_required_qty')->change();
//            $table->float('gray_unit_price')->change();
//            $table->float('gray_total_amount')->change();
//            $table->float('gray_fabric_consumption_qty')->change();
//        });
//        Schema::table('budget_dyeing_components', function (Blueprint $table) {
//            // $table->float('dyeing_fab_req_qty')->change();
//            // $table->float('price_per_kg')->change();
//            // $table->float('dyeing_total')->change();
//        });
//        Schema::table('budget_yarn_components', function (Blueprint $table) {
//            // $table->float('yarn_req_qty')->change();
//            // $table->float('price_per_kg')->change();
//            // $table->float('total')->change();
//            // $table->float('fabric_req_qty')->change();
//            // $table->float('finish_fab_dia')->change();
//        });
//        Schema::table('budget_knitting_components', function (Blueprint $table) {
//            // $table->float('knitting_fabric_req_qty')->change();
//            // $table->float('knitting_fabric_price_per_kg')->change();
//            // $table->float('knitting_total')->change();
//        });
    }
}
