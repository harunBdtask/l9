<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSubmissionInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_submission_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('document_submission_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('export_lc_id')->nullable();
            $table->unsignedBigInteger('sales_contract_id')->nullable();
            $table->unsignedBigInteger('export_invoice_id')->nullable();
            $table->string('bl_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('net_inv_value', 30)->nullable();
            $table->json('po_ids')->nullable();
            $table->unsignedBigInteger('factory_id');
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
        Schema::dropIfExists('document_submission_invoices');
    }
}
