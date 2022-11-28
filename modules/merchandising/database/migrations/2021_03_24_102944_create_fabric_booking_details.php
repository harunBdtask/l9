<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricBookingDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_booking_details', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->string('style_name')->nullable();
            $table->string('po_no')->nullable();
            $table->string('item_name')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('body_part_id')->nullable();
            $table->string('body_part_value')->nullable();
            $table->string('body_part_type')->nullable();
            $table->unsignedInteger('fabric_composition_id')->nullable();
            $table->string('fabric_composition_value')->nullable();
            $table->string('construction')->nullable();
            $table->string('composition')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->string('supplier_value')->nullable();
            $table->string('gsm')->nullable();
            $table->string('fabric_nature_id')->nullable();
            $table->string('fabric_nature_value')->nullable();
            $table->string('fabric_source_value')->nullable();
            $table->string('fabric_source')->nullable();
            $table->string('uom')->nullable();
            $table->string('uom_value')->nullable();
            $table->string('level')->nullable();
            $table->json('breakdown')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('fabric_booking_details');
    }
}
