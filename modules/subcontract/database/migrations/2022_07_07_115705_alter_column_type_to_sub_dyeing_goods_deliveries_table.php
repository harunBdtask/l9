<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnTypeToSubDyeingGoodsDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('sub_dyeing_goods_deliveries')->truncate();
        Schema::table('sub_dyeing_goods_deliveries', function (Blueprint $table) {
            $table->json('order_id')->change();
            $table->json('order_no')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_goods_deliveries', function (Blueprint $table) {
            $table->string('order_no')->nullable()->change();
            $table->unsignedInteger('order_id')->nullable()->change();
        });
    }
}
