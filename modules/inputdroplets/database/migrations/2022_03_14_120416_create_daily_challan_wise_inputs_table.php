<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyChallanWiseInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_challan_wise_inputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('floor_id');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('color_id');
            $table->string('challan_no');
            $table->date('production_date');
            $table->string('sewing_input');
            $table->unsignedBigInteger('factory_id');
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
        Schema::dropIfExists('daily_challan_wise_inputs');
    }
}
