<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKnittingProgramColorIdToYarnRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_requisition_details', function (Blueprint $table) {
            $table->string('knitting_program_color_id')->after('yarn_requisition_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_requisition_details', function (Blueprint $table) {
            $table->dropColumn('knitting_program_color_id');
        });
    }
}
