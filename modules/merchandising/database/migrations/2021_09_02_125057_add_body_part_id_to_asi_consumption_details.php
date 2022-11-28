<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBodyPartIdToAsiConsumptionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asi_consumption_details', function (Blueprint $table) {
            $table->unsignedInteger('body_part_id')->nullable()->after('group_id');
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
            $table->dropColumn('body_part_id');
        });
    }
}
