<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBreakdownIdInFabricSalesOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_sales_order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('fabric_sales_order_details','breakdown_id')){
                $table->unsignedInteger('breakdown_id')->nullable()->after('fabric_sales_order_id');
            }
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
            if (Schema::hasColumn('fabric_sales_order_details','breakdown_id')){
                $table->dropColumn('breakdown_id');
            }
        });
    }
}
