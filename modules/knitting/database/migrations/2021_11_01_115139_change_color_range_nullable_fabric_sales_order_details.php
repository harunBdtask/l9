<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColorRangeNullableFabricSalesOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_sales_order_details', function (Blueprint $table) {
            $table->string('color_range')->nullable()->change();
            $table->unsignedInteger('color_range_id')->nullable()->change();
        });
    }
}
