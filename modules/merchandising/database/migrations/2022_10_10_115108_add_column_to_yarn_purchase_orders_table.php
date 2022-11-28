<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToYarnPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_purchase_orders', function (Blueprint $table) {
            $table->date('garment_production_schedule')->nullable()->after('remarks');
            $table->text('order_note')->nullable()->after('garment_production_schedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['garment_production_schedule', 'order_note']);
        });
    }
}
