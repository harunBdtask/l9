<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualTotalProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_total_production_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('subcontract_factory_id')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('garments_item_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->integer('cutting_qty')->default(0);
            $table->integer('cutting_rejection_qty')->default(0);
            $table->integer('print_sent_qty')->default(0);
            $table->integer('print_receive_qty')->default(0);
            $table->integer('print_rejection_qty')->default(0);
            $table->integer('embroidery_sent_qty')->default(0);
            $table->integer('embroidery_receive_qty')->default(0);
            $table->integer('embroidery_rejection_qty')->default(0);
            $table->integer('wash_sent_qty')->default(0);
            $table->integer('wash_receive_qty')->default(0);
            $table->integer('wash_rejection_qty')->default(0);
            $table->integer('special_works_sent_qty')->default(0);
            $table->integer('special_works_receive_qty')->default(0);
            $table->integer('special_works_rejection_qty')->default(0);
            $table->integer('others_sent_qty')->default(0);
            $table->integer('others_receive_qty')->default(0);
            $table->integer('others_rejection_qty')->default(0);
            $table->integer('input_qty')->default(0);
            $table->integer('sewing_output_qty')->default(0);
            $table->integer('sewing_rejection_qty')->default(0);
            $table->integer('iron_qty')->default(0);
            $table->integer('iron_rejection_qty')->default(0);
            $table->integer('poly_qty')->default(0);
            $table->integer('poly_rejection_qty')->default(0);
            $table->timestamps();

            $table->index('factory_id');
            $table->index(['buyer_id', 'order_id']);
            $table->index('purchase_order_id');
            $table->index('garments_item_id');
            $table->index(['color_id', 'size_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_total_production_reports');
    }
}
