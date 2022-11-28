<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BudgetFabricBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('budget_fabric_booking', function (Blueprint $table) {
//            $table->increments('id');
//            $table->unsignedInteger('budget_id')->nullable();
//            $table->unsignedInteger('buyer_id')->nullable();
//            $table->unsignedInteger('order_id')->nullable();
//            $table->string('purchase_order_id')->nullable();
//            $table->unsignedInteger('source_id')->nullable();
//            $table->unsignedInteger('garments_color_id');
//            $table->unsignedInteger('garments_part_id');
//            $table->string('fabric_composition');
//            $table->unsignedInteger('composition_fabric_id');
//            $table->unsignedInteger('fabric_type_id');
//            $table->string('fabric_gsm');
//            $table->string('size_id');
//            $table->string('cutable_dia');
//            $table->string('finish_dia');
//            $table->unsignedInteger('finish_type');
//            $table->integer('part_wise_qty');
//            $table->float('consumption');
//            $table->string('unit_consumption');
//            $table->float('actual_req_qty');
//            $table->unsignedInteger('uom_id');
//            $table->float('process_loss');
//            $table->float('total_fabric_qty');
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
//        Schema::drop('budget_fabric_booking');
    }
}
