<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGreyWeightFabricToSubDyeingGoodsDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_goods_delivery_details', function (Blueprint $table) {
            $table->string('grey_weight_fabric')->nullable()->after('total_roll');
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
            $table->dropColumn('grey_weight_fabric');
        });
    }
}
