<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportInvoiceShippingInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_invoice_shipping_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('export_invoice_id');
            $table->string('bl_cargo_no')->nullable();
            $table->date('bl_cargo_date')->nullable();
            $table->date('original_bl_rcv_date')->nullable();

            $table->string('doc_handover')->nullable();
            $table->string('custom_forwarder_name')->nullable();
            $table->string('etd')->nullable();
            $table->string('feeder_vessel')->nullable();
            $table->string('mother_vessel')->nullable();
            $table->date('eta_date')->nullable();
            $table->string('eta_destination')->nullable();
            $table->date('ic_received_date')->nullable();
            $table->string('inco_term', 10)->nullable();
            $table->string('inco_term_place')->nullable();
            $table->string('shipping_bill_no')->nullable();
            $table->date('shipping_bill_date')->nullable();
            $table->string('port_of_entry')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('internal_file_no', 50)->nullable();

            $table->unsignedTinyInteger('shipping_mode')->nullable();
            $table->string('freight_amount_by_supplier', 30)->nullable();
            $table->date('ex_factory_date')->nullable();
            $table->date('actual_ship_date')->nullable();
            $table->string('freight_amount_by_buyer', 30)->nullable();
            $table->string('total_carton_qty', 10)->nullable();
            $table->string('category_no')->nullable();
            $table->string('hs_code', 30)->nullable();

            $table->date('advice_date')->nullable();
            $table->string('advice_amount', 30)->nullable();
            $table->string('paid_amount', 30)->nullable();

            $table->unsignedTinyInteger('incentive_applicable')->comment('1=Yes,2=No');
            $table->string('gsp_no')->nullable();
            $table->date('gsp_date')->nullable();
            $table->string('yarn_cons_per_pcs', 10)->nullable();
            $table->string('co_no')->nullable();

            $table->date('co_date')->nullable();
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
        Schema::dropIfExists('export_invoice_shipping_information');
    }
}
