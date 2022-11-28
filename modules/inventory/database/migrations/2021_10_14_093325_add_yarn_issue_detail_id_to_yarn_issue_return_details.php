<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYarnIssueDetailIdToYarnIssueReturnDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_issue_return_details', function (Blueprint $table) {
            if(!Schema::hasColumn('yarn_issue_return_details','yarn_issue_detail_id')){
                $table->string('yarn_issue_detail_id')->after('yarn_issue_return_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
