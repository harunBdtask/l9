<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsStoreDeliveryChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_store_delivery_challans', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('booking_id');
            $table->string('booking_no');
            $table->date('booking_date')->nullable();
            $table->string('challan_no');
            $table->date('challan_date');
            $table->string('challan_type')->nullable();
            $table->string('challan_qty')->nullable();
            $table->string('booking_qty')->nullable();
            $table->string('excess_delivery_qty')->nullable();
            $table->string('pi_no')->nullable();
            $table->string('delivery_from')->nullable();
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
        Schema::dropIfExists('trims_store_delivery_challans');
    }
}
