<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrnCustomerColumnToGsInvVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gs_inv_vouchers', function (Blueprint $table) {
            $table->unsignedBigInteger("trn_customer")->nullable()->after("trn_with");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gs_inv_vouchers', function (Blueprint $table) {
            $table->dropColumn("trn_customer");
        });
    }
}
