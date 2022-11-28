<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColumnAndDeleteSomeColumnToBfVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_vouchers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('file_no');
            $table->dropColumn('general_particulars');
            $table->unsignedInteger('receive_bank_id')->nullable()->after('bank_id');
            $table->string('receive_cheque_no')->nullable()->after('cheque_no');
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
            $table->unsignedInteger('company_id')->after('trn_date');
            $table->string('file_no')->nullable()->after('currency_id');
            $table->string('general_particulars')->nullable()->after('amount');
            $table->dropColumn('receive_bank_id');
            $table->dropColumn('receive_cheque_no');
        });
    }
}
