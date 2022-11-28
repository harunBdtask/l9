<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierBillPaymentBillNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_bill_payment_bill_nos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_payment_id');
            $table->unsignedBigInteger('bill_entry_id');

            $table->foreign('bill_payment_id')->references('id')->on('supplier_bill_payments')->onDelete('cascade');
            $table->foreign('bill_entry_id')->references('id')->on('supplier_bill_entries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_bill_payment_bill_nos');
    }
}
