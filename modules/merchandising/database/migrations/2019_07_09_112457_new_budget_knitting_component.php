<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewBudgetKnittingComponent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::drop('budget_knitting_components');
//        Schema::create('budget_knitting_components', function (Blueprint $table) {
//            $table->increments('id');
//            $table->unsignedInteger('budget_id')->nullable();
//            $table->unsignedInteger('buyer_id')->nullable();
//            $table->unsignedInteger('order_id')->nullable();
//            $table->string('purchase_order_id')->nullable();
//            $table->unsignedInteger('knitting_part_supplier_id');
//            $table->unsignedInteger('knitting_part_fabric_type_id');
//            $table->string('knitting_part_fabric_composition');
//            $table->unsignedInteger('knitting_part_composition_fabric_id');
//            $table->float('knitting_part_fabric_gsm');
//            $table->string('knitting_part_yarn_count');
//            $table->float('knitting_part_knitting_qty');
//            $table->float('knitting_part_knitting_unit_price');
//            $table->float('knitting_part_knitting_total');
//            $table->float('factory_id');
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
//        Schema::drop('budget_knitting_components');
    }
}
