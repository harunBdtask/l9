<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polies', function (Blueprint $table) {
            $table->increments('id');
            $table->date('production_date')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->integer('poly_qty')->deafult(0);
            $table->integer('poly_rejection_qty')->default(0);
            $table->integer('iron_qty')->default(0);
            $table->integer('iron_rejection_qty')->default(0);
            $table->integer('packing_qty')->default(0);
            $table->integer('packing_rejection_qty')->default(0);
            $table->string('reason')->nullable();
            $table->unsignedInteger('factory_id');
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('polies');
    }
}
