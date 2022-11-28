<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarkerTypeColumnToAsiConsumptionDetailsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asi_consumption_details', function (Blueprint $table) {
            $table->string('marker_type')->after('efficiency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asi_consumption_details', function (Blueprint $table) {
            $table->dropColumn('marker_type');
        });
    }
}
