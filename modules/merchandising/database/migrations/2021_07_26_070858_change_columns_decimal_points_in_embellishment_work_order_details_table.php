<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsDecimalPointsInEmbellishmentWorkOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('embellishment_work_order_details', function (Blueprint $table) {
            $table->decimal('current_work_order_qty', 10, 4)->change();
            $table->decimal('total_qty', 10, 4)->change();
            $table->decimal('total_amount', 10, 4)->change();
            $table->decimal('balance_amount', 10, 4)->change();
            $table->decimal('work_order_qty', 10, 4)->change();
            $table->decimal('balance_qty', 10, 4)->change();
            $table->decimal('work_order_rate', 10, 4)->change();
            $table->decimal('work_order_amount', 10, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('embellishment_work_order_details', function (Blueprint $table) {
            $table->decimal('current_work_order_qty', 8, 2)->change();
            $table->decimal('total_qty', 8, 2)->change();
            $table->decimal('total_amount', 8, 2)->change();
            $table->decimal('balance_amount', 8, 2)->change();
            $table->decimal('work_order_qty', 8, 2)->change();
            $table->decimal('balance_qty', 8, 2)->change();
            $table->decimal('work_order_rate', 8, 2)->change();
            $table->decimal('work_order_amount',8, 2)->change();
        });
    }
}
