<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSupplierBillPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_bill_payments', function (Blueprint $table) {

            $table->dropColumn('total_paid_amt');
            $table->dropColumn('total_discount_amt');
            $table->dropColumn('total_net_amt');
            $table->dropColumn('bill_number');
            // $table->json('bill_number')->nullable()->change();
            $table->double('total_paid_amount',11,2)->nullable();
            $table->double('total_discount',11,2)->nullable();
            $table->double('total_due_amount',11,2)->nullable();
            $table->double('total_net_payment',11,2)->nullable();
            $table->double('total_bill_amount',11,2)->nullable();
            $table->double('final_paid_amount',11,2)->nullable();
            $table->double('final_paid_amount_bdt',11,2)->nullable();
            $table->double('final_gain_loss',11,2)->nullable();
            $table->json('details')->nullable();
            $table->json('payments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_bill_payments', function (Blueprint $table) {

            $table->double('total_paid_amt',11,2)->nullable();
            $table->double('total_discount_amt',11,2)->nullable();
            $table->double('total_net_amt',11,2)->nullable();
            $table->string('bill_number')->nullable();
            
            $table->dropColumn('total_paid_amount');
            $table->dropColumn('total_discount');
            $table->dropColumn('total_due_amount');
            $table->dropColumn('total_net_payment');
            $table->dropColumn('total_bill_amount');
            $table->dropColumn('final_paid_amount');
            $table->dropColumn('final_paid_amount_bdt');
            $table->dropColumn('final_gain_loss');
            $table->dropColumn('details');
            $table->dropColumn('payments');
        });
    }
}
