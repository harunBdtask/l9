<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportProceedRealizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_proceed_realizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('beneficiary_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('document_submission_id');
            $table->unsignedBigInteger('export_lc_id')->nullable();
            $table->unsignedBigInteger('sales_contract_id')->nullable();
            $table->date('receive_date')->nullable();
            $table->string('lc_sc_no')->nullable();
            $table->unsignedInteger('currency_id')->nullable();

            $table->date('bill_invoice_date')->nullable();
            $table->string('bill_invoice_amount', 30)->nullable();
            $table->string('negotiated_amount', 30)->nullable();
            $table->string('document_currency', 30)->nullable();
            $table->string('domestic_currency', 30)->nullable();

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
        Schema::dropIfExists('export_proceed_realizations');
    }
}
