<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesContractAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_contract_amendments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('beneficiary_id');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('applicant_id')->nullable();
            $table->unsignedInteger('notifying_party_id')->nullable();
            $table->unsignedInteger('consignee_id')->nullable();
            $table->unsignedInteger('lien_bank_id')->nullable();
            $table->unsignedInteger('contract_id');
            $table->unsignedInteger('amendment_no')->nullable();

            $table->date('lien_date')->nullable();
            $table->date('last_shipment_date');
            $table->date('expiry_date')->nullable();
            $table->date('contract_date');
            $table->date('amendment_date');


            $table->string('internal_file_no', 30);
            $table->string('contract_number', 30)->nullable();

            $table->string('year');
            $table->float('contract_value', 10, 2);
            $table->float('amendment_value', 10, 2)->nullable();
            $table->string('value_changed_by', 10);
            $table->float('claim_adjustment', 10, 2)->nullable();
            $table->string('claim_adjusted_by', 10);

            $table->string('currency', 10);
            $table->string('convertible_to', 10)->nullable();
            $table->float('tolerance_percent')->nullable();
            $table->string('shipping_mode', 20);
            $table->string('pay_term', 20);
            $table->string('tenor')->nullable();
            $table->string('inco_term', 20);
            $table->string('inco_term_place')->nullable();
            $table->string('contract_source')->nullable();
            $table->string('port_of_entry')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('shipping_line')->nullable();
            $table->unsignedInteger('doc_present_days')->nullable();

            $table->float('btb_limit_percent', 4, 2)->nullable();
            $table->float('foreign_comn_percent', 4, 2)->nullable();
            $table->float('local_comn_percent', 4, 2)->nullable();
            $table->string('discount_clauses')->nullable();
            $table->string('bl_clause')->nullable();
            $table->string('export_item_category')->nullable();
            $table->string('remarks')->nullable();

            $table->unsignedInteger('amended')->default(0);

            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('sales_contract_amendments');
    }
}
