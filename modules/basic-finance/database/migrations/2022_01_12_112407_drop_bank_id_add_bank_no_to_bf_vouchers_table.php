<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBankIdAddBankNoToBfVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_vouchers', function (Blueprint $table) {
            $table->dropColumn('bank_id');
            $table->string('bank_no')->nullable()->after('from');
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
            $table->dropColumn('bank_no');
            $table->string('bank_id')->nullable()->after('from');
        });
    }
}
