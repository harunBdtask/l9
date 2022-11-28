<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateTableWiseCutProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_table_wise_cut_production_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('production_date');
            $table->unsignedInteger('cutting_floor_id');
            $table->unsignedInteger('cutting_table_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->unsignedInteger('size_id');
            $table->integer('cutting_qty')->default(0);
            $table->integer('cutting_rejection_qty')->default(0);
            $table->unsignedInteger('factory_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('cutting_floor_id')->references('id')->on('cutting_floors')->onDelete('cascade');
            $table->foreign('cutting_table_id')->references('id')->on('cutting_tables')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_table_wise_cut_production_reports');
    }
}
