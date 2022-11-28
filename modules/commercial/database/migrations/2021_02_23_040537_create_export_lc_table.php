<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportLcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_lc', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('beneficiary_id');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('applicant_id')->nullable();
            $table->unsignedInteger('notifying_party_id')->nullable();
            $table->unsignedInteger('consignee_id')->nullable();
            $table->unsignedInteger('lien_bank_id')->nullable();
            $table->unsignedInteger('doc_present_days')->nullable();

            $table->date('lc_date');
            $table->date('lien_date')->nullable();
            $table->date('last_shipment_date')->nullable();
            $table->date('lc_expiry_date')->nullable();
            $table->string('year');

            $table->float('lc_value', 10, 2)->nullable();
            $table->float('tolerance_percent')->nullable();
            $table->float('btb_limit_percent', 4, 2)->nullable();
            $table->float('foreign_comn_percent', 4, 2)->nullable();
            $table->float('local_comn_percent', 4, 2)->nullable();

            $table->string('internal_file_no', 30);
            $table->string('bank_file_no', 30);
            $table->string('lc_number', 30)->nullable();
            $table->string('currency', 10);
            $table->string('issuing_bank', 30)->nullable();
            $table->string('shipping_mode', 20);
            $table->string('pay_term', 20);
            $table->string('tenor')->nullable();
            $table->string('inco_term', 20);
            $table->string('inco_term_place')->nullable();
            $table->string('lc_source')->nullable();
            $table->string('port_of_entry')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('transferring_bank_ref', 30)->nullable();
            $table->string('transferable', 10)->nullable();
            $table->string('replacement_lc', 10)->nullable();
            $table->string('transferring_bank')->nullable();
            $table->string('negotiating_bank')->nullable();
            $table->string('nominated_ship_line')->nullable();
            $table->string('re_imbursing_bank')->nullable();
            $table->string('claim_adjustment')->nullable();
            $table->string('expiry_place')->nullable();
            $table->string('reason')->nullable();
            $table->string('bl_clause')->nullable();
            $table->string('reimbursement_clauses')->nullable();
            $table->string('discount_clauses')->nullable();
            $table->string('export_item_category')->nullable();
            $table->string('remarks')->nullable();

            $table->unsignedInteger('amended')->default(0);
            $table->date('expiry_date')->nullable();


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
        Schema::dropIfExists('export_lc');
    }
}
