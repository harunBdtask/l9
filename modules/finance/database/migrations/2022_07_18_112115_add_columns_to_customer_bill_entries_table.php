<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCustomerBillEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_bill_entries', function (Blueprint $table) {
            $table->string('gate_pass_no')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('driver_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_bill_entries', function (Blueprint $table) {
            $table->dropColumn('gate_pass_no');
            $table->dropColumn('vehicle_no');
            $table->dropColumn('driver_name');
        });
    }
}
