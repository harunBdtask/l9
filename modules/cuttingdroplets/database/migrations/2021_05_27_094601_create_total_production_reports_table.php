<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_production_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->integer('todays_cutting')->default(0);
            $table->integer('total_cutting')->default(0);
            $table->integer('todays_cutting_rejection')->default(0);
            $table->integer('total_cutting_rejection')->default(0);
            $table->integer('todays_sent')->default(0);
            $table->integer('total_sent')->default(0);
            $table->integer('todays_received')->default(0);
            $table->integer('total_received')->default(0);
            $table->integer('todays_print_rejection')->default(0);
            $table->integer('total_print_rejection')->default(0);
            $table->integer('todays_embroidary_sent')->default(0);
            $table->integer('total_embroidary_sent')->default(0);
            $table->integer('todays_embroidary_received')->default(0);
            $table->integer('total_embroidary_received')->default(0);
            $table->integer('todays_embroidary_rejection')->default(0);
            $table->integer('total_embroidary_rejection')->default(0);
            $table->integer('todays_input')->default(0);
            $table->integer('total_input')->default(0);
            $table->integer('todays_sewing_output')->default(0);
            $table->integer('total_sewing_output')->default(0);
            $table->integer('todays_sewing_rejection')->default(0);
            $table->integer('total_sewing_rejection')->default(0);
            $table->integer('todays_washing_sent')->default(0);
            $table->integer('total_washing_sent')->default(0);
            $table->integer('todays_washing_received')->default(0);
            $table->integer('total_washing_received')->default(0);
            $table->integer('todays_washing_rejection')->default(0);
            $table->integer('total_washing_rejection')->default(0);
            $table->integer('todays_received_for_poly')->default(0);
            $table->integer('total_received_for_poly')->default(0);
            $table->integer('todays_poly')->default(0);
            $table->integer('todays_poly_rejection')->default(0);
            $table->integer('total_poly')->default(0);
            $table->integer('total_poly_rejection')->default(0);
            $table->integer('todays_iron')->default(0);
            $table->integer('todays_iron_rejection')->default(0);
            $table->integer('total_iron')->default(0);
            $table->integer('total_iron_rejection')->default(0);
            $table->integer('todays_packing')->default(0);
            $table->integer('todays_packing_rejection')->default(0);
            $table->integer('total_packing')->default(0);
            $table->integer('total_packing_rejection')->default(0);
            $table->integer('todays_cartoon')->default(0);
            $table->integer('total_cartoon')->default(0);
            $table->integer('todays_pcs')->default(0);
            $table->integer('total_pcs')->default(0);
            $table->integer('todays_shipment_qty')->default(0);
            $table->integer('total_shipment_qty')->default(0);
            $table->unsignedInteger('factory_id');
            $table->timestamps();

            $table->index(['buyer_id', 'order_id']);
            $table->index('purchase_order_id');
            $table->index('color_id');

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
        Schema::dropIfExists('total_production_reports');
    }
}
