<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayModeToVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('credit_account', 30)->nullable()->after('paymode');
            $table->string('cheque_no')->nullable()->after('to');
            $table->date('cheque_date')->nullable()->after('cheque_no');
            $table->date('cheque_due_date')->nullable()->after('cheque_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('credit_account');
            $table->dropColumn('cheque_no');
            $table->dropColumn('cheque_date');
            $table->dropColumn('cheque_due_date');
        });
    }
}
