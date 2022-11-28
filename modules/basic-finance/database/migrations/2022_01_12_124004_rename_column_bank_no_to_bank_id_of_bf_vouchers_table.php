<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnBankNoToBankIdOfBfVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_vouchers', function (Blueprint $table) {
            $table->renameColumn('bank_no', 'bank_id');
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
            $table->renameColumn('bank_id', 'bank_no');
        });
    }
}
