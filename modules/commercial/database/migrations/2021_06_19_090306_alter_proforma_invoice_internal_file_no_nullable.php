<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProformaInvoiceInternalFileNoNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->string('internal_file_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->string('internal_file_no')->nullable(false)->change();
        });
    }
}
