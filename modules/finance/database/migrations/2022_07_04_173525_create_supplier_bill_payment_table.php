<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierBillPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_bill_payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->tinyInteger('currency_id')->nullable();
            $table->string('bill_number')->nullable();
            $table->date('payment_date')->nullable();
            $table->double('total_paid_amt',11,2)->nullable();
            $table->double('total_discount_amt',11,2)->nullable();
            $table->double('total_net_amt',11,2)->nullable();
            $table->tinyInteger('pay_currency_id')->nullable();
            $table->double('pay_con_rate',8,2)->nullable();
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
        Schema::dropIfExists('supplier_bill_payments');
    }
}
