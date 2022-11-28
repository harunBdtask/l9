<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PoWiseRecapReport extends Migration
{
    public function up()
    {
        Schema::create('po_wise_recap_report_table', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_id')->nullable();
            $table->unsignedInteger('buyer')->nullable();
            $table->string('booking_no')->nullable();
            $table->string('order_style_no')->nullable();
            $table->string('po_no')->nullable();
            $table->string('item_id')->nullable();
            $table->string('fabrication')->nullable();
            $table->string('fab_special')->nullable();
            $table->string('gsm')->nullable();
            $table->string('item')->nullable();
            $table->string('t_shirt')->nullable();
            $table->string('polo')->nullable();
            $table->string('pant')->nullable();
            $table->string('intimate')->nullable();
            $table->string('others')->nullable();
            $table->string('order_qty')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('total_value')->nullable();
            $table->string('cm_dozon')->nullable();
            $table->date('shipment_date')->nullable();
            $table->string('print')->nullable();
            $table->string('emb')->nullable();
            $table->string('fac')->nullable();
            $table->string('pp')->nullable();
            $table->string('remarks')->nullable();

            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('po_wise_recap_report_table');
    }
}
