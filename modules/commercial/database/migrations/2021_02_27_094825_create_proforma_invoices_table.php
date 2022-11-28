<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProformaInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proforma_invoices', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('approval_user_id')->nullable();
            $table->string('beneficiary')->nullable();
            $table->unsignedTinyInteger('item_category');
            $table->string('file_path')->nullable();
            $table->unsignedTinyInteger('goods_rcv_status')->nullable();
            $table->unsignedTinyInteger('source');
            $table->string('hs_code', 30)->nullable();
            $table->unsignedInteger('tenor')->nullable();

            $table->unsignedBigInteger('importer_id');
            $table->string('indentor_name')->nullable();

            $table->date('pi_receive_date');
            $table->date('last_shipment_date')->nullable();
            $table->date('pi_validity_date')->nullable();

            $table->string('currency', 10);
            $table->string('lc_group_no', 30)->nullable();
            $table->string('pay_term', 30);
            $table->string('pi_basis', 30);
            $table->string('pi_no', 30);
            $table->string('internal_file_no', 30);
            $table->string('pi_for', 60)->nullable();
            $table->string('priority', 60)->nullable();

            $table->enum('ready_to_approve', ['yes', 'no']);

            $table->json('details')->nullable();

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
        Schema::dropIfExists('proforma_invoices');
    }
}
