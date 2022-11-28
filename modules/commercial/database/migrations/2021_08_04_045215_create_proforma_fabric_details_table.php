<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProformaFabricDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proforma_fabric_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proforma_invoice_id')->nullable();
            $table->string('gsm')->nullable();
            $table->string('uom')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->string('rate')->nullable();
            $table->string('type')->nullable();
            $table->string('color')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->text('po_nos')->nullable();
            $table->json('purchase_order_ids')->nullable();
            $table->string('wo_no')->nullable();
            $table->string('amount')->nullable();
            $table->string('hs_code')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('body_part')->nullable();
            $table->unsignedInteger('body_part_id')->nullable();
            $table->string('quantity')->nullable();
            $table->string('dia')->nullable();
            $table->string('dia_type')->nullable();
            $table->string('dia_type_value')->nullable();
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->unsignedBigInteger('details_id')->nullable();
            $table->string('style_name')->nullable();
            $table->string('composition')->nullable();
            $table->string('construction')->nullable();
            $table->string('style_unique_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('fabric_composition_id')->nullable();
            $table->json('contrast_color_id')->nullable();
            $table->text('contrast_colors')->nullable();
            $table->unsignedInteger('garments_item_id')->nullable();
            $table->string('garments_item')->nullable();
            $table->unsignedInteger('color_type_id')->nullable();
            $table->string('color_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proforma_fabric_details');
    }
}
