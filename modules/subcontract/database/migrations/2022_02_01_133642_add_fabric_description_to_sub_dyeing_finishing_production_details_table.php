<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFabricDescriptionToSubDyeingFinishingProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_finishing_production_details', function (Blueprint $table) {
            $table->text('fabric_description')->nullable()->after('gsm');
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
            $table->dropColumn('fabric_description');
        });
    }
}
