<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountRealizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_realizations', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('realization_type_source')->comment("1=Manual, 2=Auto");
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('bf_project_id');
            $table->unsignedBigInteger('bf_unit_id');
            $table->tinyInteger('realization_type')->comment("1=LDBC, 2=FDBC,3=TT");
            $table->unsignedBigInteger('document_submission_id')->nullable();
            $table->unsignedBigInteger('commercial_realization_id')->nullable();
            $table->string('realization_number')->nullable();
            $table->json('export_lc_id')->nullable();
            $table->json('sales_contract_id')->nullable();
            $table->json('export_invoice_id')->nullable();
            $table->json('sc_number')->nullable();
            $table->json('lc_number')->nullable();
            $table->date('realization_date')->nullable();
            $table->string('realization_rate', 30)->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->json('total_value')->nullable();
            $table->json('realized_value')->nullable();
            $table->json('short_realization')->nullable();
            $table->json('foreign_bank_charge')->nullable();
            $table->json('deduction')->nullable();
            $table->json('total_deduction')->nullable();
            $table->json('distribution')->nullable();
            $table->json('loan_distribution')->nullable();
            $table->json('total_distribution')->nullable();
            $table->json('grand_total')->nullable();
            $table->string('realized_gain_loss')->nullable();
            $table->string('realized_difference')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_realizations');
    }
}
