<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrimsStoreReceiveIdToV3TrimsStoreIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('v3_trims_store_issues', function (Blueprint $table) {
            $table->unsignedBigInteger('trims_store_receive_id')->nullable()->after('challan_no');
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
            $table->dropColumn('trims_store_receive_id');
        });
    }
}
