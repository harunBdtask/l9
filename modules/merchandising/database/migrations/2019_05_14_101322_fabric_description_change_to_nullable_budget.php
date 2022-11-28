<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FabricDescriptionChangeToNullableBudget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_yarn_components', function (Blueprint $table) {
            $table->text('yarn_description')->nullable()->change();
        });
        Schema::table('budget_knitting_components', function (Blueprint $table) {
            $table->text('knitting_fabric_description')->nullable()->change();
        });
        Schema::table('budget_gray_fabric_components', function (Blueprint $table) {
            $table->text('gray_fabric_description')->nullable()->change();
        });
        Schema::table('budget_direct_fabric_components', function (Blueprint $table) {
            $table->text('fabric_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('budget_yarn_components', function (Blueprint $table) {
        //     $table->text('yarn_description')->change();
        // });
        // Schema::table('budget_knitting_components', function (Blueprint $table) {
        //     $table->text('knitting_fabric_description')->change();
        // });
        // Schema::table('budget_gray_fabric_components', function (Blueprint $table) {
        //     $table->text('gray_fabric_description')->change();
        // });
        // Schema::table('budget_direct_fabric_components', function (Blueprint $table) {
        //     $table->text('fabric_description')->change();
        // });
    }
}
