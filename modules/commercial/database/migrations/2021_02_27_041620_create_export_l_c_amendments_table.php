<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportLCAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_lc_amendments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('beneficiary_id');
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('lien_bank_id')->nullable();
            $table->unsignedInteger('amendment_no')->nullable();
            $table->unsignedInteger('contract_id')->nullable();



            $table->date('lc_date');
            $table->date('lc_expiry_date')->nullable();
            $table->date('amendment_date');
            $table->date('last_shipment_date');
            $table->date('expiry_date')->nullable();

            $table->float('lc_value', 10, 2)->nullable();
            $table->float('tolerance_percent')->nullable();
            $table->float('amendment_value', 10, 2)->nullable();
            $table->float('claim_adjustment', 10, 2)->nullable();

            $table->string('currency', 10);
            $table->string('replacement_lc', 10)->nullable();
            $table->string('value_changed_by', 10);
            $table->string('shipping_mode', 20);
            $table->string('inco_term_place')->nullable();
            $table->string('port_of_entry')->nullable();
            $table->string('port_of_loading')->nullable();
            $table->string('port_of_discharge')->nullable();
            $table->string('pay_term', 20);
            $table->string('tenor')->nullable();
            $table->string('claim_adjusted_by', 10);
            $table->string('discount_clauses')->nullable();
            $table->string('remarks')->nullable();
            $table->string('export_item_category')->nullable();
            $table->string('internal_file_no', 30);
            $table->string('lc_number', 30)->nullable();
            $table->string('year');


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
        Schema::dropIfExists('export_lc_amendments');
    }
}
