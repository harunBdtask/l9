<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddColumnToSubDyeingFinishingProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_finishing_production_details', function (Blueprint $table) {
            $table->date('production_date')->after('sub_textile_order_no');
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
            $table->dropColumn(['production_date']);
        });
    }
}
