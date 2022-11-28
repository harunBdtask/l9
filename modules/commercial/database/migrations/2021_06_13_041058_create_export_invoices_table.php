<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('uniq_id', 20);
            $table->unsignedBigInteger('export_lc_id')->nullable();
            $table->unsignedBigInteger('sales_contract_id')->nullable();
            $table->string('invoice_no');
            $table->unsignedBigInteger('buyer_id');
            $table->date('invoice_date');
            $table->string('exp_form_no', 20)->nullable();
            $table->date('exp_form_date')->nullable();
            $table->unsignedBigInteger('applicant_id')->nullable();
            $table->unsignedBigInteger('lien_bank_id')->nullable();
            $table->unsignedBigInteger('beneficiary_id')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->text('remarks')->nullable();
            $table->string('file')->nullable();
            $table->string('additional_info')->nullable();
            $table->string('invoice_value', 30)->nullable();
            $table->string('invoice_qty', 30)->nullable();
            $table->string('add_upcharge', 30)->nullable();
            $table->string('net_invoice_value', 30)->nullable();

            /*Percentages*/
            $table->string('discount_percentage', 5)->nullable();
            $table->string('annual_bonus_percentage', 5)->nullable();
            $table->string('claim_percentage', 5)->nullable();
            $table->string('commission_percentage', 5)->nullable();
            $table->string('other_deduction_percentage', 5)->nullable();

            /*Amounts*/
            $table->string('discount_amount', 30)->nullable();
            $table->string('bonus_amount', 30)->nullable();
            $table->string('claim_amount', 30)->nullable();
            $table->string('commission_amount', 30)->nullable();
            $table->string('other_deduction_amount', 30)->nullable();

            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('export_invoices');
    }
}
