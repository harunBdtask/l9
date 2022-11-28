<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequisitionColorIdInYarnIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_issue_details', function (Blueprint $table) {
            $table->unsignedInteger('requisition_color_id')->after('demand_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_issue_details', function (Blueprint $table) {
            $table->dropColumn('requisition_color_id');
        });
    }
}
