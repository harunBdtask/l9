<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommercialRealizationInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commercial_realization_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commercial_realization_id');
            $table->date('realization_date');
            $table->unsignedBigInteger('document_submission_id');
            $table->tinyInteger('dbp_type')->nullable()->comment('1=LDBP,2=FDBP');
            $table->string('bank_ref_bill')->nullable();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('document_submission_invoice_id')->nullable();
            $table->unsignedBigInteger('primary_contract_id')->nullable();
            $table->unsignedBigInteger('export_lc_id')->nullable();
            $table->unsignedBigInteger('sales_contract_id')->nullable();
            $table->unsignedBigInteger('export_invoice_id')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('net_invoice_value', 30)->nullable();
            $table->date('document_submission_date')->nullable();
            $table->string('submission_value', 30)->nullable();
            $table->string('realized_value', 30)->nullable();
            $table->string('short_realized_value', 30)->nullable();
            $table->string('due_realized_value', 30)->nullable();
            $table->unsignedBigInteger('factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commercial_realization_invoices');
    }
}
