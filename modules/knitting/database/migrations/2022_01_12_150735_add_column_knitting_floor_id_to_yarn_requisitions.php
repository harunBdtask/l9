<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnKnittingFloorIdToYarnRequisitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_requisitions', function (Blueprint $table) {
            $table->unsignedBigInteger('knitting_floor_id')->after('program_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_requisitions', function (Blueprint $table) {
            $table->dropColumn('knitting_floor_id');
        });
    }
}
