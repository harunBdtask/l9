<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewBudgetDyeingComponent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::drop('budget_dyeing_components');
//        Schema::create('budget_dyeing_components', function (Blueprint $table) {
//            $table->increments('id');
//            $table->unsignedInteger('budget_id')->nullable();
//            $table->unsignedInteger('buyer_id')->nullable();
//            $table->unsignedInteger('order_id')->nullable();
//            $table->string('purchase_order_id')->nullable();
//            $table->unsignedInteger('dyeing_part_supplier_id');
//            $table->unsignedInteger('dyeing_part_fabric_type_id');
//            $table->string('dyeing_part_fabric_composition');
//            $table->unsignedInteger('dyeing_part_composition_fabric_id');
//            $table->unsignedInteger('factory_id');
//            $table->float('dyeing_part_fabric_gsm');
//            $table->string('dyeing_part_yarn_count');
//            $table->float('dyeing_part_dyeing_qty');
//            $table->float('dyeing_part_dyeing_cost');
//            $table->float('dyeing_part_aop_cost');
//            $table->float('dyeing_part_peached_cost');
//            $table->float('dyeing_part_brushed_cost');
//            $table->float('dyeing_part_finishing_cost');
//            $table->float('dyeing_part_total_cost');
//            $table->softDeletes();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::drop('budget_dyeing_components');
    }
}
