<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPiInfoToSupplierBillEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_bill_entries', function (Blueprint $table) {
            $table->string('pi_no')->nullable()->after('entry_type');
            $table->string('pi_value')->nullable()->after('pi_no');
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
            $table->dropColumn('pi_no');
            $table->dropColumn('pi_value');
        });
    }
}
