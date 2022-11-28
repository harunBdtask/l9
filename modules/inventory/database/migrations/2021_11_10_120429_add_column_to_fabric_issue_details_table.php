<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToFabricIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_issue_details', function (Blueprint $table) {
            $table->string('fabric_receive_id')->after('unique_id')->nullable();
            $table->string('fabric_receive_details_id')->after('fabric_receive_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_issue_details', function (Blueprint $table) {
            $table->dropColumn('fabric_receive_id');
            $table->dropColumn('fabric_receive_details_id');
        });
    }
}
