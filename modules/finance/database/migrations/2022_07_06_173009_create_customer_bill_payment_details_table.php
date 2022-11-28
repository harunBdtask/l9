<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBillPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bill_payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_bill_payment_id');
            $table->string('bill_no')->nullable();
            $table->string('order_no')->nullable();
            $table->string('bill_date')->nullable();
            $table->string('cons_rate')->nullable();
            $table->string('bill_amount')->nullable();
            $table->string('prev_received')->nullable();
            $table->string('current_out_standing')->nullable();
            $table->string('received_amount')->nullable();
            $table->string('discount')->nullable();
            $table->string('due_amount')->nullable();
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
        Schema::dropIfExists('customer_bill_payment_details');
    }
}
