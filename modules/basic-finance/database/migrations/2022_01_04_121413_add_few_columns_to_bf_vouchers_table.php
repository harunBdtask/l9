<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFewColumnsToBfVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_vouchers', function (Blueprint $table) {
            $table->string('voucher_no')->nullable()->after('id');
            $table->unsignedInteger('company_id')->after('trn_date');
            $table->unsignedInteger('project_id')->after('company_id');
            $table->unsignedInteger('unit_id')->after('project_id');
            $table->unsignedInteger('currency_id')->after('unit_id');
            $table->string('reference_no')->nullable()->after('file_no');
            $table->tinyInteger('paymode')->comment('1 = Bank; 2 = Cash;')->after('reference_no');
            $table->string('credit_account',30)->nullable()->after('paymode');
            $table->string('debit_account',30)->nullable()->after('credit_account');
            $table->string('to')->nullable()->after('debit_account');
            $table->string('from')->nullable()->after('to');
            $table->unsignedInteger('bank_id')->nullable()->after('from');
            $table->string('cheque_no')->nullable()->after('bank_id');
            $table->date('cheque_date')->nullable()->after('cheque_no');
            $table->date('cheque_due_date')->nullable()->after('cheque_date');
            $table->unsignedInteger('deleted_by')->nullable()->after('updated_by');
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
        Schema::table('bf_vouchers', function (Blueprint $table) {
            $table->dropColumn('voucher_no');
            $table->dropColumn('company_id');
            $table->dropColumn('project_id');
            $table->dropColumn('unit_id');
            $table->dropColumn('currency_id');
            $table->dropColumn('reference_no');
            $table->dropColumn('paymode');
            $table->dropColumn('credit_account');
            $table->dropColumn('debit_account');
            $table->dropColumn('to');
            $table->dropColumn('from');
            $table->dropColumn('bank_id');
            $table->dropColumn('cheque_no');
            $table->dropColumn('cheque_date');
            $table->dropColumn('cheque_due_date');
            $table->dropColumn('deleted_by');
            $table->dropColumn('deleted_at');
        });
    }
}
