<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsWorkOrderDoneAllBudgetComponentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_direct_fabric_components', function (Blueprint $table) {
            if (! Schema::hasColumn('budget_direct_fabric_components', 'is_work_order_create')) {
                $table->integer('is_work_order_create')->default(0)->comment('0 = No work order , 1 = work order created');
            }
            $table->integer('fabric_type')->comment('1 = Knit , 2 = Woven')->change();
        });
        Schema::table('budget_dyeing_components', function (Blueprint $table) {
            if (! Schema::hasColumn('budget_dyeing_components', 'is_work_order_create')) {
                $table->integer('is_work_order_create')->default(0)->comment('0 = No work order , 1 = work order created');
            }
            $table->integer('dyeing_source')->comment('1 = purchase , 2 = in-house , 3 = subcontract')->change();
            $table->dropColumn('supplier_id');
        });
        Schema::table('budget_gray_fabric_components', function (Blueprint $table) {
            if (! Schema::hasColumn('budget_gray_fabric_components', 'is_work_order_create')) {
                $table->integer('is_work_order_create')->default(0)->comment('0 = No work order , 1 = work order created');
            }
            $table->integer('gray_fabric_source')->comment('1 = purchase , 2 = in-house , 3 = subcontract')->change();
            $table->integer('gray_fabric_type')->comment('1 = Knit , 2 = Woven')->change();
        });
        Schema::table('budget_knitting_components', function (Blueprint $table) {
            if (! Schema::hasColumn('budget_knitting_components', 'is_work_order_create')) {
                $table->integer('is_work_order_create')->default(0)->comment('0 = No work order , 1 = work order created');
            }
            $table->integer('knitting_source')->comment('1 = purchase , 2 = in-house , 3 = subcontract')->change();
        });
        Schema::table('budget_others_components', function (Blueprint $table) {
            if (! Schema::hasColumn('budget_others_components', 'is_work_order_create')) {
                $table->integer('is_work_order_create')->default(0)->comment('0 = No work order , 1 = work order created');
            }
        });
        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
            if (! Schema::hasColumn('budget_trims_accessories_components', 'is_work_order_create')) {
                $table->integer('is_work_order_create')->default(0)->comment('0 = No work order , 1 = work order created');
            }
        });
        Schema::table('budget_weaving_components', function (Blueprint $table) {
            if (! Schema::hasColumn('budget_weaving_components', 'is_work_order_create')) {
                $table->integer('is_work_order_create')->default(0)->comment('0 = No work order , 1 = work order created');
            }
            $table->integer('woven_source')->comment('1 = purchase , 2 = in-house , 3 = subcontract')->change();
            $table->integer('woven_fabric_type')->comment('1 = Knit , 2 = Woven')->change();
        });
        Schema::table('budget_yarn_components', function (Blueprint $table) {
            if (! Schema::hasColumn('budget_yarn_components', 'is_work_order_create')) {
                $table->integer('is_work_order_create')->default(0)->comment('0 = No work order , 1 = work order created');
            }
            $table->integer('yarn_source')->comment('1 = purchase , 2 = in-house , 3 = subcontract')->change();
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
