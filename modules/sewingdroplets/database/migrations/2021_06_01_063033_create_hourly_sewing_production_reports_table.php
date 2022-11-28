<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourlySewingProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_sewing_production_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('production_date');
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('line_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->integer('hour_0')->default(0);
            $table->integer('hour_1')->default(0);
            $table->integer('hour_2')->default(0);
            $table->integer('hour_3')->default(0);
            $table->integer('hour_4')->default(0);
            $table->integer('hour_5')->default(0);
            $table->integer('hour_6')->default(0);
            $table->integer('hour_7')->default(0);
            $table->integer('hour_8')->default(0);
            $table->integer('hour_9')->default(0);
            $table->integer('hour_10')->default(0);
            $table->integer('hour_11')->default(0);
            $table->integer('hour_12')->default(0);
            $table->integer('hour_13')->default(0);
            $table->integer('hour_14')->default(0);
            $table->integer('hour_15')->default(0);
            $table->integer('hour_16')->default(0);
            $table->integer('hour_17')->default(0);
            $table->integer('hour_18')->default(0);
            $table->integer('hour_19')->default(0);
            $table->integer('hour_20')->default(0);
            $table->integer('hour_21')->default(0);
            $table->integer('hour_22')->default(0);
            $table->integer('hour_23')->default(0);
            $table->integer('sewing_rejection')->default(0);
            $table->unsignedInteger('factory_id');
            $table->timestamps();

            $table->index('production_date');
            $table->index(['floor_id', 'line_id']);
            $table->index(['buyer_id', 'order_id']);
            $table->index('purchase_order_id');
            $table->index('color_id');

            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
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
        Schema::dropIfExists('hourly_sewing_production_reports');
    }
}
