<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleBookingBeforeOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_booking_before_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_booking_id');
            $table->unsignedInteger('requisition_id');
            $table->unsignedInteger('requisition_detail_id');
            $table->json('po_id')->nullable();
            $table->json('sample_id');
            $table->unsignedInteger('body_part_id');
            $table->unsignedInteger('gmts_item_id');
            $table->unsignedInteger('fabric_nature_id');
            $table->unsignedInteger('gmts_color_id');
            $table->unsignedInteger('color_type_id');
            $table->unsignedInteger('fabric_description_id');
            $table->unsignedInteger('fabric_source_id');
            $table->string('dia', 10)->nullable();
            $table->string('gsm', 20)->nullable();
            $table->unsignedInteger('uom_id');
            $table->string('required_qty', 20);
            $table->string('process_loss', 20)->nullable();
            $table->string('rate', 20);
            $table->string('total_qty', 20);
            $table->string('amount', 20);
            $table->string('remarks')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('sample_booking_before_order_details');
    }
}
