<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFabricIssues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_issues', function (Blueprint $table) {
            $table->enum('status', [0, 1])->default(0)->comment('0 = false, 1 = true')
                ->after('cutt_req_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_issues', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
