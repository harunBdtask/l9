<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWashingReceivedManualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('washing_received_manuals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('challan_no', 25)->index();
            $table->unsignedInteger('buyer_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedInteger('purchase_order_id')->index();
            $table->unsignedInteger('color_id')->index();
            $table->unsignedInteger('received_qty')->default(0);
            $table->unsignedInteger('rejection_qty')->default(0);
            $table->string('reasons', 100)->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('factory_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('washing_received_manuals');
    }
}
