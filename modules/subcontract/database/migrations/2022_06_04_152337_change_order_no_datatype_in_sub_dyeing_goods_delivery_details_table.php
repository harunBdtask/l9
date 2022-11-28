<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOrderNoDatatypeInSubDyeingGoodsDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_goods_delivery_details', function (Blueprint $table) {
            $table->string('batch_no')->nullable()->change();
            $table->string('order_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_goods_delivery_details', function (Blueprint $table) {
            $table->unsignedInteger('order_no')->nullable()->change();
            $table->unsignedInteger('batch_no')->nullable()->change();
        });
    }
}
