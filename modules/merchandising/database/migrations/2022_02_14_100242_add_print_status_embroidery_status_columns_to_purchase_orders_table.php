<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrintStatusEmbroideryStatusColumnsToPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('print_status')
                ->after('remarks')
                ->default(2)
                ->comment('1=Yes,2=No');
            $table->string('embroidery_status')
                ->after('print_status')
                ->default(2)
                ->comment('1=Yes,2=No');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('print_status');
            $table->dropColumn('embroidery_status');
        });
    }
}
