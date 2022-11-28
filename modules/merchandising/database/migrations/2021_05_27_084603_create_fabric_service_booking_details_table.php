<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricServiceBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_service_booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('budget_id');
            $table->unsignedInteger('service_booking_id');
            $table->string('style_name', 50);
            $table->string('po_no');
            $table->string('fabric_description');
            $table->unsignedInteger('gmts_color_id');
            $table->unsignedInteger('item_color_id');
            $table->string('labdip_no')->nullable();
            $table->string('lot')->nullable();
            $table->unsignedInteger('yarn_count_id')->nullable();
            $table->unsignedInteger('yarn_composition_id')->nullable();
            $table->unsignedInteger('brand_id')->nullable();
            $table->unsignedInteger('mc_dia')->nullable();
            $table->unsignedInteger('finish_dia')->nullable();
            $table->unsignedInteger('finish_gsm')->nullable();
            $table->string('stich_length')->nullable();
            $table->unsignedInteger('mc_gauge')->nullable();
            $table->unsignedInteger('uom_id');
            $table->decimal('balance_qty', 12, 4);
            $table->decimal('wo_qty', 12, 4);
            $table->decimal('rate', 12, 4);
            $table->decimal('amount', 12, 4);
            $table->date('delivery_date')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('fabric_service_booking_details');
    }
}
