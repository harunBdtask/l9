<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDateWiseWarehouseInOutReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_wise_warehouse_in_out_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('production_date')->index();
            $table->unsignedInteger('buyer_id')->index();
            $table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('purchase_order_id')->index();
            $table->unsignedInteger('factory_id')->index();
            $table->integer('in_garments_qty')->default(0);
            $table->integer('in_carton_qty')->default(0);
            $table->integer('out_garments_qty')->default(0);
            $table->integer('out_carton_qty')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_wise_warehouse_in_out_reports');
    }
}
