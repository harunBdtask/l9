<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportDocumentAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_document_acceptances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('btb_margin_lc_id');
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->unsignedBigInteger('lien_bank_id')->nullable();
            $table->date('shipment_date')->nullable();
            $table->string('document_value')->nullable();
            $table->string('lc_value')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->date('bank_acc_date')->nullable();
            $table->date('company_acc_date')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedTinyInteger('acceptance_time')
                ->nullable()
                ->comment("1=After Goods Receive,2=Before Goods Receive");
            $table->string('bank_ref')->nullable();
            $table->unsignedInteger('importer_id')->nullable()->comment('factories table id');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('retire_source_id')->nullable();
            $table->string('pay_term', 30)
                ->nullable()
                ->comment('at_sight, usance, cash_in_advance, open_account');

            $table->unsignedTinyInteger('lc_type')
                ->default(1)
                ->comment('1=BTB LC,2=Margin LC');

            $table->json('pi_ids')->nullable()->comment('proforma_invoices');
            $table->string('pi_value', 30)->nullable();


            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('import_document_shipping_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imp_doc_acc_id')
                ->comment('import_document_acceptances table PK');

            $table->unsignedBigInteger('bl_cargo_no')->nullable();
            $table->date('bl_cargo_date')->nullable();
            $table->string('shipment_mode', 30)
                ->nullable()
                ->comment('sea, air, road, train, sea/air, road/air');

            $table->unsignedTinyInteger('document_status')
                ->nullable()->comment('1=Original, 2=Copy');

            $table->date('copy_doc_receive_date')->nullable();
            $table->date('document_to_cf')->nullable();
            $table->string('feeder_vessel')->nullable();
            $table->string('mother_vessel')->nullable();
            $table->date('eta_date')->nullable();
            $table->date('ic_received_date')->nullable();
            $table->string('shipping_bill_no')->nullable();
            $table->string('inco_term', 15)->nullable();
            $table->string('inco_term_place', 100)->nullable();
            $table->string('port_of_loading', 100)->nullable();
            $table->string('port_of_discharge', 100)->nullable();
            $table->string('internal_file_no', 100)->nullable();
            $table->string('bill_of_entry_no', 100)->nullable();
            $table->string('psi_reference_no', 100)->nullable();
            $table->date('maturity_date')->nullable();
            $table->string('container_no', 100)->nullable();
            $table->string('package_quantity', 50)->nullable();
            $table->unsignedInteger('factory_id');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('import_document_pi_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imp_doc_acc_id')
                ->comment('import_document_acceptances table PK');
            $table->unsignedBigInteger('pi_id')
                ->nullable()->comment('proforma_invoices table PK');
            $table->unsignedBigInteger('item_id')
                ->nullable()->comment('items table id');

            $table->string('pi_value', 20)->nullable();
            $table->string('current_acceptance_value', 20)->nullable();
            $table->string('mrr_value', 20)->nullable();
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
        Schema::dropIfExists('import_document_acceptances');
        Schema::dropIfExists('import_document_shipping_infos');
        Schema::dropIfExists('import_document_pi_infos');
    }
}
