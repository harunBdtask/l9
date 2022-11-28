<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishingProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finishing_production_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('floor_id')->index();
            $table->unsignedInteger('line_id')->index();
            $table->unsignedInteger('buyer_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedInteger('purchase_order_id')->index();
            $table->unsignedInteger('color_id')->index();
            $table->date('production_date')->index();
            $table->integer('sewing_input')->default(0);
            $table->integer('sewing_output')->default(0);
            $table->integer('sewing_rejection')->default(0);
            $table->unsignedInteger('factory_id')->index();
            $table->timestamps();

            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
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
        Schema::dropIfExists('finishing_production_reports');
    }
}
