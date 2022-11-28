<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToFabricCostDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_cost_details', function (Blueprint $table) {
            $table->decimal("fabric_cons")->after("gsm");
            $table->decimal("rate")->after("fabric_cons");
            $table->decimal("amount")->after("rate");
            $table->json("fabric_consumption_details")->nullable()->after("amount");
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
            //
        });
    }
}
