<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectionColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('order_status_id')->after('fabric_type')->nullable()->comment('1 : Confirm, 2 : Projection');
            $table->string('projection_po')->after('order_status_id')->nullable();
            $table->unsignedInteger('projection_qty')->after('projection_po')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_status_id', 'projection_po', 'projection_qty']);
        });
    }
}
