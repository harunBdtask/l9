<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateAndColorWiseProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_and_color_wise_productions', function (Blueprint $table) {
            $table->increments('id');
            $table->date('production_date');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->integer('cutting_qty')->default(0);
            $table->integer('cutting_rejection_qty')->default(0);
            $table->integer('print_sent_qty')->default(0);
            $table->integer('print_received_qty')->default(0);
            $table->integer('print_rejection_qty')->default(0);
            $table->integer('embroidary_sent_qty')->default(0);
            $table->integer('embroidary_received_qty')->default(0);
            $table->integer('embroidary_rejection_qty')->default(0);
            $table->integer('input_qty')->default(0);
            $table->integer('sewing_output_qty')->default(0);
            $table->integer('sewing_rejection_qty')->default(0);
            $table->integer('washing_sent_qty')->default(0);
            $table->integer('washing_received_qty')->default(0);
            $table->integer('washing_rejection_qty')->default(0);
            $table->integer('poly_qty')->default(0);
            $table->integer('received_for_poly')->default(0);
            $table->integer('total_cartoon')->default(0);
            $table->integer('total_pcs')->default(0);
            $table->integer('poly_rejection')->default(0);
            $table->integer('iron_qty')->default(0);
            $table->integer('iron_rejection_qty')->default(0);
            $table->integer('packing_qty')->default(0);
            $table->integer('packing_rejection_qty')->default(0);
            $table->integer('ship_qty')->default(0);
            $table->unsignedInteger('factory_id');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['production_date', 'buyer_id']);
            $table->index(['order_id', 'color_id']);
            $table->index('purchase_order_id');

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
        Schema::dropIfExists('date_and_color_wise_productions');
    }
}
