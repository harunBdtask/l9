<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnAllocationBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_allocation_booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yarn_allocation_id');
            $table->unsignedBigInteger('garments_item_id')->nullable();
            $table->unsignedBigInteger('body_part_id')->nullable();
            $table->unsignedBigInteger('color_type_id')->nullable();
            $table->text('fabric_description')->nullable();
            $table->string('fabric_gsm')->nullable();
            $table->string('fabric_dia')->nullable();
            $table->unsignedBigInteger('dia_type_id')->nullable();
            $table->string('dia_type')->nullable();
            $table->unsignedBigInteger('gmt_color_id')->nullable();
            $table->string('gmt_color')->nullable();
            $table->unsignedBigInteger('item_color_id')->nullable();
            $table->string('item_color')->nullable();
            $table->unsignedBigInteger('color_range_id')->nullable();
            $table->string('color_range')->nullable();
            $table->integer('cons_uom')->nullable();
            $table->string('booking_qty')->nullable();
            $table->string('average_price', 30)->nullable();
            $table->string('amount', 30)->nullable();
            $table->integer('prog_uom')->nullable();
            $table->string('finish_qty')->nullable();
            $table->string('process_loss')->nullable();
            $table->string('gray_qty')->nullable();
            $table->unsignedBigInteger('process_id')->nullable();
            $table->unsignedBigInteger('fabric_nature_id')->nullable();
            $table->string('fabric_nature')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yarn_allocation_booking_details');
    }
}
