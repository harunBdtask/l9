<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInspectionDateUnitPriceInPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
//            $table->date('inspection_date')->nullable()->after('smv');
//            $table->string('unit_price')->nullable()->after('inspection_date');
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
//            $table->dropColumn('inspection_date');
//            $table->dropColumn('unit_price');
        });
    }
}
