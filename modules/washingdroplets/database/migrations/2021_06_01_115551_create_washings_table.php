<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWashingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('washings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bundle_card_id');
            $table->string('washing_challan_no', 25);
            $table->boolean('status')->default(0);
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->unsignedInteger('size_id')->nullable();
            $table->string('washing_received_challan_no')->nullable();
            $table->boolean('received_status')->default(0);
            $table->boolean('received_challan_status')->default(0);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('factory_id');
            $table->softDeletes();
            $table->timestamps();

            $table->index('bundle_card_id');
            $table->index('washing_challan_no');
            $table->index(['buyer_id', 'order_id']);
            $table->index('purchase_order_id');
            $table->index('color_id');
            $table->index('size_id');
            $table->index('washing_received_challan_no');

            $table->foreign('bundle_card_id')->references('id')->on('bundle_cards')->onDelete('cascade');
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
        Schema::dropIfExists('washings');
    }
}
