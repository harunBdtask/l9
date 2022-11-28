<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsInFabricSalesOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_sales_order_details', function (Blueprint $table) {
            $table->string('cus_buyer')->after('id')->nullable();
            $table->string('cus_style')->after('cus_buyer')->nullable();
            $table->string('ld_no')->after('item_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_sales_order_details', function (Blueprint $table) {
            $table->dropColumn('cus_buyer');
            $table->dropColumn('cus_style');
            $table->dropColumn('ld_no');
        });
    }
}
