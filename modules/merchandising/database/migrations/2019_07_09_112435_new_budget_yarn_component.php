<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewBudgetYarnComponent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::drop('budget_yarn_components');
//        Schema::create('budget_yarn_components', function (Blueprint $table) {
//            $table->increments('id');
//            $table->unsignedInteger('budget_id')->nullable();
//            $table->unsignedInteger('buyer_id')->nullable();
//            $table->unsignedInteger('order_id')->nullable();
//            $table->string('purchase_order_id')->nullable();
//            $table->unsignedInteger('yarn_part_source');
//            $table->unsignedInteger('yarn_part_fabric_type_id');
//            $table->string('yarn_part_fabric_composition');
//            $table->unsignedInteger('yarn_part_composition_fabric_id');
//            $table->integer('yarn_part_fabric_gsm');
//            $table->float('yarn_part_total_yarn_quantity');
//            $table->string('yarn_part_yarn_count');
//            $table->float('yarn_part_yarn_unit_price');
//            $table->float('yarn_part_total_yarn_value');
//            $table->unsignedInteger('is_work_order_create')->nullable()->comment = '	0 = No work order , 1 = work order created';
//            $table->unsignedInteger('factory_id');
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
//        Schema::drop('budget_yarn_components');
    }
}
