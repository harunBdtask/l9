<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBudgetForMultiplePurchaseOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_components', function (Blueprint $table) {
            $table->string('purchase_order_id')->unsigned()->nullable()->change();
        });
        Schema::table('budget_masters', function (Blueprint $table) {
            $table->string('purchase_order_id')->unsigned()->nullable()->change();
        });
        Schema::table('budget_direct_fabric_components', function (Blueprint $table) {
            $table->string('purchase_order_id')->unsigned()->nullable()->change();
        });
        Schema::table('budget_dyeing_components', function (Blueprint $table) {
            $table->string('purchase_order_id')->unsigned()->nullable()->change();
        });
        Schema::table('budget_gray_fabric_components', function (Blueprint $table) {
            $table->string('purchase_order_id')->unsigned()->nullable()->change();
        });
        Schema::table('budget_knitting_components', function (Blueprint $table) {
            $table->string('purchase_order_id')->unsigned()->nullable()->change();
        });
        Schema::table('budget_others_components', function (Blueprint $table) {
            $table->string('purchase_order_id')->unsigned()->nullable()->change();
            $table->integer('total_garments')->nullable();
        });
        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
            $table->string('purchase_order_id')->unsigned()->nullable()->change();
        });
        Schema::table('budget_yarn_components', function (Blueprint $table) {
            $table->string('purchase_order_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
