<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsOrderToOrderTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_order_to_order_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->string('challan_no');
            $table->date('transfer_date');
            $table->json('from_order')->nullable();
            $table->json('to_order')->nullable();
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
        Schema::dropIfExists('trims_order_to_order_transfers');
    }
}