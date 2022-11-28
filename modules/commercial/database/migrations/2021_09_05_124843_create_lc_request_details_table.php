<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLcRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lc_request_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lc_request_id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->string('style_name')->nullable();
            $table->string('po_no')->nullable();
            $table->string('customer')->nullable();
            $table->string('description')->nullable();
            $table->string('ship_mode')->nullable();
            $table->double('po_quantity')->nullable();
            $table->double('rate')->nullable();
            $table->double('amount')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('co')->nullable();
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
        Schema::dropIfExists('lc_request_details');
    }
}
