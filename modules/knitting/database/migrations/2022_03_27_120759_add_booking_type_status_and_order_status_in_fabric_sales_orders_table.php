<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingTypeStatusAndOrderStatusInFabricSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_sales_orders', function (Blueprint $table) {
            $table->tinyInteger('booking_type_status')->nullable()->after('remarks')->comment('1 = Sample, 2 = Bulk');
            $table->tinyInteger('order_status')->nullable()->after('booking_type_status')->comment('1 = Projection, 2 = Confirmed, 3 = Canceled, 4 = in-house');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_sales_orders', function (Blueprint $table) {
            $table->dropColumn('booking_type_status');
            $table->dropColumn('order_status');
        });
    }
}
