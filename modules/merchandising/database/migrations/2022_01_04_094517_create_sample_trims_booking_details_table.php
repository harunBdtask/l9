<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleTrimsBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_trims_booking_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sample_trims_booking_id');

            $table->string('requisition_no')->nullable();
            $table->string('style_name')->nullable();
            $table->string('item_names')->nullable();
            $table->string('item_des')->nullable();
            $table->string('uom_values')->nullable();
            $table->string('req_qty')->nullable();
            $table->string('cu_wo')->nullable();
            $table->string('balance_wo_qty')->nullable();
            $table->string('wo_qty')->nullable();
            $table->string('rate', 30)->nullable();
            $table->string('amount')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('sample_trims_booking_details');
    }
}
