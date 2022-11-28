<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBatchIdAndBatchNoToSubDyeingGoodsDeliveriesTable extends Migration
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
            $table->json('batch_id')->change();
            $table->json('batch_no')->change();
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
            $table->unsignedInteger('batch_id')->change();
            $table->string('batch_no', 40)->change();
        });
    }
}
