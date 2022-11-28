<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFabricCostDetailsAddUomColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_cost_details', function (Blueprint $table) {
            $table->string("uom", 20)
                ->nullable()
                ->after("dia_type")
                ->comment("1=Kg,2=Yards,3=Meter,4=Pcs");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_cost_details', function (Blueprint $table) {
            $table->dropColumn("uom");
        });
    }
}
