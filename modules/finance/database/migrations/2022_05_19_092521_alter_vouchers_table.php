<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->after('reference_no');
            $table->unsignedInteger('currency_id')->after('unit_id');
            $table->string('debit_account', 30)->nullable()->after('credit_account');
            $table->string('from')->nullable()->after('to');
            $table->string('bank_id')->nullable()->after('from');
            $table->string('receive_bank_id')->nullable()->after('bank_id');
            $table->string('receive_cheque_no')->nullable()->after('cheque_no');
            $table->unsignedInteger('deleted_by')->nullable()->after('updated_by');
            $table->softDeletes();

            $table->dropColumn('company_id');
            $table->dropColumn('department_id');
            $table->dropColumn('check_no');
            $table->dropColumn('general_particulars');
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
            $table->unsignedInteger('company_id')->nullable()->after('trn_date');
            $table->unsignedInteger('department_id')->nullable()->after('unit_id');
            $table->unsignedInteger('check_no')->nullable()->after('credit_account');
            $table->string('general_particulars')->nullable()->after('amount');

            $table->dropColumn('project_id');
            $table->dropColumn('currency_id');
            $table->dropColumn('debit_account');
            $table->dropColumn('from');
            $table->dropColumn('bank_id');
            $table->dropColumn('receive_bank_id');
            $table->dropColumn('receive_cheque_no');
            $table->dropColumn('deleted_by');
            $table->dropColumn('deleted_at');
        });
    }
}
