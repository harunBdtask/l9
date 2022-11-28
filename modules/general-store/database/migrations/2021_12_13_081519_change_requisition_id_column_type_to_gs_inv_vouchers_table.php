<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRequisitionIdColumnTypeToGsInvVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gs_inv_vouchers', function (Blueprint $table) {
            $table->dropColumn("requisition_id");
            $table->string("requisition_s_code")->nullable()->after("store");
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
            $table->unsignedInteger('requisition_id')->nullable();
            $table->dropColumn("requisition_s_code");
        });
    }
}
