<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuyerIdColumnInPlanningInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planning_infos', function (Blueprint $table) {
            $table->unsignedInteger('buyer_id')->after('buyer_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planning_infos', function (Blueprint $table) {
            $table->dropColumn('buyer_id');
        });
    }
}
