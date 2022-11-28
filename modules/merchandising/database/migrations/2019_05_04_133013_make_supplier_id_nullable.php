<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSupplierIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_dyeing_components', function (Blueprint $table) {
            $table->unsignedInteger('dyeing_supplier')->nullable()->change();
        });
        Schema::table('budget_gray_fabric_components', function (Blueprint $table) {
            $table->unsignedInteger('supplier_id')->nullable()->change();
        });
        Schema::table('budget_knitting_components', function (Blueprint $table) {
            $table->unsignedInteger('knitting_supplier_id')->nullable()->change();
        });
        Schema::table('budget_yarn_components', function (Blueprint $table) {
            $table->unsignedInteger('yarn_supplier_id')->nullable()->change();
        });
        Schema::table('budget_weaving_components', function (Blueprint $table) {
            $table->unsignedInteger('woven_supplier_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
