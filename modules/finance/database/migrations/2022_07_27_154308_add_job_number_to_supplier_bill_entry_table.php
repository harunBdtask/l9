<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobNumberToSupplierBillEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_bill_entries', function (Blueprint $table) {
            $table->unsignedInteger('job_number')->nullable()->after('party_payable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_bill_entries', function (Blueprint $table) {
            $table->dropColumn('job_number');
        });
    }
}
