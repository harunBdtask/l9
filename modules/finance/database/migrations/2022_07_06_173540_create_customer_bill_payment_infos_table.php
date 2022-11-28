<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBillPaymentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bill_payment_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_bill_payment_id');
            $table->date('received_date')->nullable();
            $table->string('total_received')->nullable();
            $table->string('discount_received')->nullable();
            $table->string('net_received')->nullable();
            $table->string('currency')->nullable();
            $table->string('cons_rate')->nullable();
            $table->json('details')->nullable();
            $table->string('exchange_gain_loss')->nullable();
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
        Schema::dropIfExists('customer_bill_payment_infos');
    }
}
