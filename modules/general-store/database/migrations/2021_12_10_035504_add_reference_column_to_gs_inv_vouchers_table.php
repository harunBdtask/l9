<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceColumnToGsInvVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gs_inv_vouchers', function (Blueprint $table) {
            $table->string('reference')->nullable()->after("requisition_id");
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
            $table->dropColumn("reference");
        });
    }
}
