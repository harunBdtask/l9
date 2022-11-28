<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountSupplierTaxVatInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_supplier_tax_vat_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_supplier_id')->constrained('account_suppliers')->cascadeOnDelete();
            $table->string('tax_tin_number', 30)->nullable();
            $table->float('tax_rate')->nullable();
            $table->string('vat_tin_number', 30)->nullable();
            $table->float('vat_rate')->nullable();
            $table->tinyInteger('vat_type')->default(0)->comment('1 = Including, 2 = Excluding');
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
        Schema::dropIfExists('account_supplier_tax_vat_infos');
    }
}
