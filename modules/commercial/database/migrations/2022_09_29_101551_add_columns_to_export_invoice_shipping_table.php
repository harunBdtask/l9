<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToExportInvoiceShippingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('export_invoice_shipping_information', function (Blueprint $table) {
            $table->string('inco_term_year')->nullable();
            $table->string('payment_term')->nullable();
            $table->string('final_destination')->nullable();
            $table->string('erc_no')->nullable();
            $table->string('beneficiary_declaration')->nullable();
            $table->string('lc_issue_bank')->nullable();
            $table->string('bin_no')->nullable();
            $table->string('pi_no')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('export_invoice_shipping_information', function (Blueprint $table) {
            $table->dropColumn('inco_term_year');
            $table->dropColumn('payment_term');
            $table->dropColumn('final_destination');
            $table->dropColumn('erc_no');
            $table->dropColumn('beneficiary_declaration');
            $table->dropColumn('lc_issue_bank');
            $table->dropColumn('bin_no');
            $table->dropColumn('pi_no');
        });
    }
}
