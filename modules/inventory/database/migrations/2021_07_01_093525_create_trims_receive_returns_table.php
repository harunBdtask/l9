<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsReceiveReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_receive_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->date('return_date')->nullable();
            $table->string('returned_source')->nullable();
            $table->string('returned_to')->nullable();
            $table->unsignedInteger('store_id')->nullable();
            $table->string('gate_pass_no')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->string('year')->nullable();
            $table->string('unique_id')->nullable();
            $table->string('style_name')->nullable();
            $table->string('po_no')->nullable();
            $table->double('po_quantity')->nullable();
            $table->string('order_uom')->nullable();
            $table->unsignedInteger('order_uom_id')->nullable();
            $table->string('shipment_date')->nullable();


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
        Schema::dropIfExists('trims_receive_returns');
    }
}
