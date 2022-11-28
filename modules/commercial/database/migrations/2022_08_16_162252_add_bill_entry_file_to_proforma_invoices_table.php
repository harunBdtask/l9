<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillEntryFileToProformaInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proforma_invoices', function (Blueprint $table) {
            $table->string('bill_entry_file')->nullable()->after('file_path');
            $table->string('import_docs')->nullable()->after('bill_entry_file');
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
            $table->dropColumn('bill_entry_file');
            $table->dropColumn('import_docs');
        });
    }
}
