<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIssueDateToV3TrimsStoreIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('v3_trims_store_issues', function (Blueprint $table) {
            $table->date('issue_date')->after('challan_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('v3_trims_store_issues', function (Blueprint $table) {
            $table->dropColumn('issue_date');
        });
    }
}
