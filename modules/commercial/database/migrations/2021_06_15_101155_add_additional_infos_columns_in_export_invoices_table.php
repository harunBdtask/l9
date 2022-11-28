<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalInfosColumnsInExportInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('export_invoices', function (Blueprint $table) {
            $table->dropColumn('additional_info');
            $table->string('cargo_delivery_to')->nullable();
            $table->string('main_mark')->nullable();
            $table->string('net_weight', 20)->nullable();
            $table->string('cbm')->nullable();
            $table->string('place_of_delivery')->nullable();
            $table->string('side_mark')->nullable();
            $table->string('gross_weight', 20)->nullable();
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
            $table->string('additional_info')->nullable();
            $table->dropColumn([
                'cargo_delivery_to',
                'main_mark',
                'net_weight',
                'cbm',
                'place_of_delivery',
                'side_mark',
                'gross_weight',
            ]);
        });
    }
}
