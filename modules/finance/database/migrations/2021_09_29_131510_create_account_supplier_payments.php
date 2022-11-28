<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountSupplierPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_supplier_id')->constrained('account_suppliers')->cascadeOnDelete();
            $table->tinyInteger('mode')->default(1)->comment('1 = Bank, 2 = Cash');
            $table->string('cheque_name')->nullable();
            $table->tinyInteger('condition')->nullable()->comment('1 = After Invoice Date, 2 = After Bill Entry Date');
            $table->tinyInteger('payment_after')->nullable()->comment('1 = End of the month, 2 = Days');
            $table->integer('days')->nullable();
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
        Schema::dropIfExists('account_supplier_payments');
    }
}
