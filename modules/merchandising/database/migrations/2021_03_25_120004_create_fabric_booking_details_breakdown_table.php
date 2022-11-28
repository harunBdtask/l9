<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricBookingDetailsBreakdownTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_booking_details_breakdown', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('booking_id')->nullable();
            $table->string('job_no')->nullable();
            $table->string('po_no')->nullable();
            $table->string('body_part_value')->nullable();
            $table->unsignedInteger('body_part_id')->nullable();
            $table->unsignedInteger('color_type_id')->nullable();
            $table->string('color_type_value')->nullable();
            $table->string('dia_type')->nullable();
            $table->string('dia_type_value')->nullable();
            $table->string('construction')->nullable();
            $table->string('composition')->nullable();
            $table->string('gsm')->nullable();
            $table->string('item_color')->nullable();
            $table->string('color')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->string('size')->nullable();
            $table->string('size_id')->nullable();
            $table->string('dia')->nullable();
            $table->string('process_loss')->nullable();
            $table->string('balance_qty')->nullable();
            $table->string('wo_qty')->nullable();
            $table->string('adj_qty')->nullable();
            $table->string('actual_wo_qty')->nullable();
            $table->string('uom_value')->nullable();
            $table->string('uom')->nullable();
            $table->string('rate')->nullable();
            $table->string('amount')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('remarks')->nullable();

            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->unsignedInteger('deleted_by');
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
        Schema::dropIfExists('fabric_booking_details_breakdown');
    }
}
