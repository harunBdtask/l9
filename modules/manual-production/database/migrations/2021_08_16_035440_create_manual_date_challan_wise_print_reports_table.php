<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualDateChallanWisePrintReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_date_challan_wise_print_reports', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('subcontract_factory_id')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedInteger('garments_item_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->string('challan_no')->nullable();
            $table->integer('print_sent_qty')->default(0);
            $table->integer('print_receive_qty')->default(0);
            $table->integer('print_rejection_qty')->default(0);
            $table->timestamps();

            $table->index('factory_id');
            $table->index('production_date');
            $table->index(['buyer_id', 'order_id']);
            $table->index('purchase_order_id');
            $table->index('garments_item_id');
            $table->index('color_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_date_challan_wise_print_reports');
    }
}
