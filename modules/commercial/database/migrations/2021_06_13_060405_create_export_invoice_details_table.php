<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('export_invoice_id')->nullable();
            $table->unsignedBigInteger('export_lc_id')->nullable();
            $table->unsignedBigInteger('export_lc_detail_id')->nullable();
            $table->unsignedBigInteger('sales_contract_id')->nullable();
            $table->unsignedBigInteger('sales_contract_detail_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('po_id')->nullable();
            $table->string('article_no')->nullable();
            $table->date('shipment_date')->nullable();
            $table->string('attach_qty', 30)->nullable();
            $table->string('rate', 10)->nullable();
            $table->string('current_invoice_qty', 30)->nullable();
            $table->string('current_invoice_value', 30)->nullable();
            $table->string('cumu_invoice_qty', 30)->nullable();
            $table->string('po_balance_qty', 30)->nullable();
            $table->string('cumu_invoice_value', 30)->nullable();
            $table->string('ex_factory_qty', 30)->nullable();

            $table->unsignedInteger('merchandiser_id')->nullable()->comment("users table id comes from orders table dealing merchant");
            $table->unsignedTinyInteger('production_source')->comment("1=In House, 2=Out Bound Subcontract");
            $table->unsignedTinyInteger('color_size_details_status')->comment("1=Yes, 0=No");

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
        Schema::dropIfExists('export_invoice_details');
    }
}
