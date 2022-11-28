<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateFloorWisePrintEmbrReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_floor_wise_print_embr_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('production_date')->index();
            $table->unsignedInteger('cutting_floor_id')->index();
            $table->unsignedInteger('buyer_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedInteger('garments_item_id')->index();
            $table->unsignedInteger('purchase_order_id')->index();
            $table->unsignedInteger('color_id');
            $table->integer('print_sent_qty')->default(0);
            $table->integer('print_received_qty')->default(0);
            $table->integer('print_rejection_qty')->default(0);
            $table->integer('embroidery_sent_qty')->default(0);
            $table->integer('embroidery_received_qty')->default(0);
            $table->integer('embroidery_rejection_qty')->default(0);
            $table->unsignedInteger('factory_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cutting_floor_id')->references('id')->on('cutting_floors')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('date_floor_wise_print_embr_reports');
    }
}
