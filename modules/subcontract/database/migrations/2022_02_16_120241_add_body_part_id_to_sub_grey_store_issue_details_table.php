<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBodyPartIdToSubGreyStoreIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_grey_store_issue_details', function (Blueprint $table) {
            $table->unsignedInteger('body_part_id')->nullable()
                ->after('challan_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_grey_store_issue_details', function (Blueprint $table) {
            $table->dropColumn('body_part_id');
        });
    }
}
