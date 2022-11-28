<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToExportInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('export_invoices', function (Blueprint $table) {
            $table->string('notify_1')->nullable();
            $table->string('notify_1_address')->nullable();
            $table->string('notify_2')->nullable();
            $table->string('notify_2_address')->nullable();
            $table->string('also_notify')->nullable();
            $table->string('also_notify_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('export_invoices', function (Blueprint $table) {
            $table->dropColumn('notify_1');
            $table->dropColumn('notify_1_address');
            $table->dropColumn('notify_2');
            $table->dropColumn('notify_2_address');
            $table->dropColumn('also_notify');
            $table->dropColumn('also_notify_address');
        });
    }
}
