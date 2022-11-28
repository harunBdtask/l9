<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchNameBranchAddressAndSwiftCodeToBfBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_banks', function (Blueprint $table) {
            $table->string('branch_name')->nullable()->after('currency_type_id');
            $table->string('branch_address')->nullable()->after('branch_name');
            $table->string('swift_code')->nullable()->after('branch_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_banks', function (Blueprint $table) {
            $table->dropColumn('branch_name');
            $table->dropColumn('branch_address');
            $table->dropColumn('swift_code');
        });
    }
}
