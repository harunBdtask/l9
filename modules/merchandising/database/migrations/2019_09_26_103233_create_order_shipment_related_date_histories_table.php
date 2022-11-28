<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderShipmentRelatedDateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shipment_related_date_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->date('delivery_date')->nullable();
            $table->date('dyeing_date')->nullable();
            $table->date('knitting_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_shipment_related_date_histories');
    }
}
