<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBToBMarginLcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_to_b_margin_lcs', function (Blueprint $table) {
            $table->id();
            $table->string('uniq_id', 30);
            $table->unsignedInteger('factory_id');
            $table->date('application_date')->nullable();
            $table->unsignedBigInteger('lien_bank_id')->nullable();
            $table->unsignedInteger('item_id');

            $table->unsignedTinyInteger('lc_basis')
                ->default(1)
                ->comment('1=Independent,2=Pi Basis');

            $table->json('pi_ids')
                ->nullable()
                ->comment('proforma_invoices');

            $table->string('pi_value', 30)->nullable();
            $table->unsignedInteger('supplier_id');

            $table->unsignedTinyInteger('lc_type')
                ->default(1)
                ->comment('1=BTB LC,2=Margin LC');

            $table->string('lc_number', 30)->nullable();
            $table->date('lc_date')->nullable();
            $table->date('last_shipment_date')->nullable();
            $table->date('lc_expiry_date')->nullable();

            $table->string('lc_value', 30)->nullable();
            $table->string('inco_term', 15)->nullable();
            $table->string('inco_term_place', 100)->nullable();
            $table->string('pay_term', 30)
                ->nullable()
                ->comment('1 of at_sight, usance, cash_in_advance, open_account');

            $table->string('tenor', 100)->nullable();
            $table->string('tolerance_percentage', 10)->nullable();
            $table->string('delivery_mode', 30)
                ->nullable()
                ->comment('1 of sea, air, road, train, sea/air, road/air');

            $table->string('doc_present_days', 30)->nullable();
            $table->string('port_of_loading', 100)->nullable();
            $table->string('port_of_discharge', 100)->nullable();

            $table->date('etd_date')->nullable();

            $table->string('lca_no', 100)->nullable();
            $table->string('lcaf_no', 100)->nullable();
            $table->string('imp_form_no', 100)->nullable();
            $table->string('insurance_company', 100)->nullable();
            $table->string('cover_note_no', 100)->nullable();

            $table->date('cover_note_date')->nullable();
            $table->string('psi_company', 100)->nullable();

            $table->unsignedTinyInteger('maturity_from')
                ->nullable()
                ->comment("1=Acceptance Date, 2=Shipment Date, 3=Negotiation Date, 4=B/L Date");

            $table->string('margin_deposite_percentage', 100)->nullable();
            $table->string('origin', 100)->nullable();
            $table->string('shipping_mark', 100)->nullable();
            $table->string('garments_qty', 30)->nullable();

            $table->unsignedInteger('unit_of_measurement_id');

            $table->string('ud_no', 100)->nullable();
            $table->date('ud_date')->nullable();

            $table->unsignedTinyInteger('credit_to_be_advised')
                ->nullable()
                ->comment("1=Teletransmission,2=Airmail,3=Courier,4=Airmail/Courier,5=Telex,6=SWIFT");

            $table->unsignedTinyInteger('partial_shipment')
                ->default(0)
                ->comment('0=No,1=Yes');

            $table->unsignedTinyInteger('transhipment')
                ->default(1)
                ->comment('0=No,1=Yes');

            $table->unsignedTinyInteger('add_confirmation_req')
                ->default(0)
                ->comment('0=No,1=Yes');

            $table->string('add_confirming_bank', 100)->nullable();

            $table->unsignedTinyInteger('bonded_warehouse')
                ->default(0)
                ->comment('0=No,1=Yes');

            $table->unsignedTinyInteger('status')
                ->default(1)
                ->comment("1=Active, 2=Inactive, 3=Cancelled");

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
        Schema::dropIfExists('b_to_b_margin_lcs');
    }
}
