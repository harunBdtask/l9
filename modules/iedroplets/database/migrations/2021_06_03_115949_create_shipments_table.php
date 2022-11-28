<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->unsignedInteger('ship_quantity')->nullable();
            $table->integer('short_reject_qty')->default(0);
            $table->string('remarks')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('factory_id');
            $table->integer('status')->default(0)->comment="0=Pending,1=Approved";
            $table->timestamps();
            $table->softDeletes();

            $table->index(['buyer_id', 'order_id']);
            $table->index('purchase_order_id');
            $table->index(['color_id', 'size_id']);
            $table->index('factory_id');

            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
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
        Schema::dropIfExists('shipments');
    }
}
