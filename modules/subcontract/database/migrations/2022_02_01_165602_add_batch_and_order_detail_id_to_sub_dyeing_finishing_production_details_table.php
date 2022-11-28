<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchAndOrderDetailIdToSubDyeingFinishingProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_finishing_production_details', function (Blueprint $table) {
            $table->unsignedInteger('sub_dyeing_batch_details_id')->nullable()
                ->after('sub_dyeing_finishing_production_id');
            $table->unsignedInteger('sub_textile_order_details_id')->nullable()
                ->after('sub_dyeing_batch_details_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_finishing_production_details', function (Blueprint $table) {
            $table->dropColumn('sub_dyeing_batch_details_id');
            $table->dropColumn('sub_textile_order_details_id');
        });
    }
}
