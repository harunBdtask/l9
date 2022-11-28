<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeGmtColorNullableFabricSalesOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_sales_order_details', function (Blueprint $table) {
            $table->string('gmt_color')->nullable()->change();
            $table->unsignedInteger('gmt_color_id')->nullable()->change();
        });
    }
}
