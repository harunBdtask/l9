<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColoumToVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('voucher_no')->nullable()->after('id');
            $table->unsignedInteger('company_id')->nullable()->after('trn_date');
            $table->string('reference_no')->nullable()->after('file_no');
            $table->unsignedInteger('unit_id')->nullable()->after('reference_no');
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
            $table->dropColumn('voucher_no');
            $table->dropColumn('company_id');
            $table->dropColumn('reference_no');
            $table->dropColumn('unit_id');
        });
    }
}
