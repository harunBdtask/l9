<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportProceedDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_proceed_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('export_proceed_realization_id');
            $table->unsignedBigInteger('account_head_id');
            $table->unsignedBigInteger('document_submission_transaction_id')->nullable();
            $table->string('ac_loan_no', 30)->nullable();
            $table->string('document_currency', 30)->nullable();
            $table->string('conversion_rate', 10)->nullable();
            $table->string('domestic_currency', 30)->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1=Deduction at Source, 2=Distribution');
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
        Schema::dropIfExists('export_proceed_details');
    }
}
