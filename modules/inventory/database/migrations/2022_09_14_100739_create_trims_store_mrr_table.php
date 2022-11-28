<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsStoreMrrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_store_mrr', function (Blueprint $table) {
            $table->id();
            $table->string('mrr_no')->nullable();
            $table->string('trims_store_receive_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('booking_id');
            $table->string('booking_no');
            $table->date('booking_date');
            $table->string('booking_qty')->nullable();
            $table->string('booking_amount')->nullable();
            $table->string('delivery_amount')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('challan_no')->nullable();
            $table->date('qc_date')->nullable();
            $table->string('mrr_date')->nullable();
            $table->string('pi_no')->nullable();
            $table->date('pi_receive_date')->nullable();
            $table->string('others')->nullable();
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
        Schema::dropIfExists('trims_store_mrr');
    }
}
