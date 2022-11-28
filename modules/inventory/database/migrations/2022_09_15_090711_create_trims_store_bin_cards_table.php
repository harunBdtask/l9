<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsStoreBinCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_store_bin_cards', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('booking_id');
            $table->string('booking_no');
            $table->unsignedBigInteger('trims_store_mrr_id');
            $table->date('booking_date')->nullable();
            $table->date('mrr_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('booking_qty')->nullable();
            $table->string('delivery_qty')->nullable();
            $table->string('excess_delivery_qty')->nullable();
            $table->string('pi_no')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('trims_store_bin_cards');
    }
}
