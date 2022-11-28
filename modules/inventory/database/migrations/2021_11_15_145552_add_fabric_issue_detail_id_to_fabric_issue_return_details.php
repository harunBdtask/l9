<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFabricIssueDetailIdToFabricIssueReturnDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_issue_return_details', function (Blueprint $table) {
            $table->unsignedInteger('fabric_issue_detail_id')->nullable()->after('issue_return_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_issue_return_details', function (Blueprint $table) {
            $table->dropColumn('fabric_issue_detail_id');
        });
    }
}
