<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('uniq_id', 30);
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->date('submission_date')->nullable();
            $table->unsignedTinyInteger('submitted_to')->default(1)->comment('1=Lien Bank');
            $table->unsignedInteger('lien_bank_id')->nullable();
            $table->string('bank_ref_bill')->nullable();
            $table->date('bank_ref_date')->nullable();
            $table->unsignedInteger('submission_type')->nullable();
            $table->date('negotiation_date')->nullable();
            $table->string('days_to_realize')->nullable();
            $table->date('possible_reali_date')->nullable();
            $table->string('courier_receipt_no', 100)->nullable();
            $table->string('courier_company', 100)->nullable();
            $table->date('gsp_courier_date')->nullable();
            $table->string('bank_to_bank_cour_no', 100)->nullable();
            $table->date('bank_to_bank_cour_date')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('document_submissions');
    }
}
