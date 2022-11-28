<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuttingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutting_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->date('plan_date');
            $table->unsignedInteger('cutting_floor_id');
            $table->unsignedInteger('cutting_table_id');
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->date('cutting_delivery_date')->nullable();
            $table->string('no_of_marker')->nullable();
            $table->float('plan_qty')->default(0);
            $table->string('rating')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('duration')->nullable();
            $table->string('text')->nullable();
            $table->string('plan_text')->nullable();
            $table->float('smv')->nullable();
            $table->float('progress')->default(0);
            $table->string('board_color')->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
            $table->foreign('cutting_floor_id')->references('id')->on('cutting_floors')->onDelete('cascade');
            $table->foreign('cutting_table_id')->references('id')->on('cutting_tables')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cutting_plans');
    }
}
