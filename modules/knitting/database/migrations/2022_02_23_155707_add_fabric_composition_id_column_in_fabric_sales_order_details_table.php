<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFabricCompositionIdColumnInFabricSalesOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_sales_order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('fabric_sales_order_details', 'fabric_composition_id')) {
                $table->unsignedInteger('fabric_composition_id')->after('color_type_id')->nullable();
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
            if (Schema::hasColumn('fabric_sales_order_details', 'fabric_composition_id')) {
                $table->dropColumn('fabric_composition_id');
            }
        });
    }
}
