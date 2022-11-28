<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrimaryMasterContractAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('primary_master_contract_amendments', function (Blueprint $table) {
            $table->id();
            $table->string('amend_no');
            $table->date('amend_date');
            $table->string('unique_id')->nullable();
            $table->unsignedBigInteger('beneficiary_id')->nullable();
            $table->unsignedBigInteger('buying_agent_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('ex_contract_number')->nullable();
            $table->string('contract_value')->nullable();
            $table->string('inco_term')->nullable();
            $table->string('inco_term_place')->nullable();
            $table->string('port_of_entry')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('shipping_mode')->nullable();
            $table->string('tolerance')->nullable();
            $table->string('contract_source')->nullable();
            $table->string('pay_term_id')->nullable();
            $table->string('pay_term_remarks')->nullable();
            $table->string('draft')->nullable();
            $table->string('shipment_remarks')->nullable();
            $table->string('presentation_period')->nullable();
            $table->string('tenor')->nullable();
            $table->string('shipping_line')->nullable();
            $table->string('doc_present_days')->nullable();
            $table->string('claim_adjustment')->nullable();
            $table->string('btb_limit_percentage')->nullable();
            $table->string('foreign_comn')->nullable();
            $table->string('export_item_category_id')->nullable();
            $table->text('document_required')->nullable();
            $table->text('document_terms')->nullable();
            $table->date('ex_cont_issue_date')->nullable();
            $table->date('shipment_date')->nullable();
            $table->date('expiry_date')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('primary_master_contract_amendments');
    }
}
