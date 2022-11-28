<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYarnIssueIdToYarnGatePassChallanScanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_gate_pass_challan_scan', function (Blueprint $table) {
            $table->string('yarn_issue_id')->nullable()->after('issue_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_gate_pass_challan_scan', function (Blueprint $table) {
            $table->dropColumn('yarn_issue_id');
        });
    }
}
