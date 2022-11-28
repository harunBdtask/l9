<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarksInFabBooking extends Migration
{
    public function up()
    {
//        Schema::table('budget_fabric_booking', function (Blueprint $table) {
//            $table->text('control')->nullable();
//            $table->text('remarks')->nullable();
//        });
//        Schema::table('budget_yarn_components', function (Blueprint $table) {
//            $table->dropColumn('yarn_part_yarn_count');
//        });
//        Schema::table('budget_yarn_components', function (Blueprint $table) {
//            $table->integer('yarn_part_yarn_count')->after('yarn_part_total_yarn_quantity');
//        });
//        Schema::table('budget_knitting_components', function (Blueprint $table) {
//            $table->dropColumn('knitting_part_yarn_count');
//        });
//        Schema::table('budget_knitting_components', function (Blueprint $table) {
//            $table->integer('knitting_part_yarn_count')->after('knitting_part_fabric_gsm');
//        });
//
//        Schema::table('budget_dyeing_components', function (Blueprint $table) {
//            $table->dropColumn('dyeing_part_yarn_count');
//        });
//        Schema::table('budget_dyeing_components', function (Blueprint $table) {
//            $table->integer('dyeing_part_yarn_count')->after('dyeing_part_fabric_gsm');
//        });
    }

    public function down()
    {
//        Schema::table('budget_fabric_booking', function (Blueprint $table) {
//            $table->dropColumn('control');
//            $table->dropColumn('remarks');
//        });
//        Schema::table('budget_yarn_components', function (Blueprint $table) {
//            $table->dropColumn('yarn_part_yarn_count');
//        });
//        Schema::table('budget_knitting_components', function (Blueprint $table) {
//            $table->dropColumn('knitting_part_yarn_count');
//        });
//        Schema::table('budget_dyeing_components', function (Blueprint $table) {
//            $table->dropColumn('dyeing_part_yarn_count');
//        });
    }
}
