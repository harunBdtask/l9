<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSubmissionTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_submission_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('document_submission_id');
            $table->unsignedInteger('account_head_id')->nullable();
            $table->string('ac_loan_no', 100)->nullable();
            $table->string('domestic_currency', 100)->nullable();
            $table->string('conversion_rate', 10)->nullable();
            $table->string('lc_sc_currency', 100)->nullable();
            $table->unsignedInteger('factory_id');
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
        Schema::dropIfExists('document_submission_transactions');
    }
}
