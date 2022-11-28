<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateAndTotalValueToSubDyeingGoodsDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_goods_delivery_details', function (Blueprint $table) {
            $table->string('rate')->nullable()->after('delivery_qty');
            $table->string('total_value')->nullable()->after('rate');
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
            $table->dropColumn('rate');
            $table->dropColumn('total_value');
        });
    }
}
