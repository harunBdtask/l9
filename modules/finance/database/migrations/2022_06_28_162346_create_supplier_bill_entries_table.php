<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierBillEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_bill_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->tinyInteger('entry_type')->default(1)->comment("1=Independent,2=GRN");
            $table->date('bill_receive_date')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->string('bill_number')->nullable();
            $table->date('bill_date')->nullable();
            $table->string('po_number')->nullable();
            $table->date('po_date')->nullable();
            $table->tinyInteger('currency_id')->nullable();
            $table->double('con_rate',8,2)->nullable();
            $table->double('discount_rate',8,2)->nullable();
            $table->json('details')->nullable();
            $table->tinyInteger('vat_type')->nullable();
            $table->double('vat_rate',8,2)->nullable();
            $table->double('total_vat',8,2)->nullable();
            $table->tinyInteger('tds')->nullable();
            $table->double('tds_rate',8,2)->nullable();
            $table->double('total_tds',8,2)->nullable();
            $table->double('party_payable',11,2)->nullable();
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
        Schema::dropIfExists('supplier_bill_entries');
    }
}
