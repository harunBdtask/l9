<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceNumberAndApproveStatusInAccountRealizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_realizations', function (Blueprint $table) {
            $table->json('invoice_number')->nullable()->after('lc_number');
            $table->tinyInteger('approve_status')->default(0)->after('realized_difference')->comment("0=Not Approved,1=Aproved");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_realizations', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'approve_status']);
        });
    }
}
