<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrimsStoreMrrDetailIdToTrimsStoreIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_store_issue_details', function (Blueprint $table) {
            $table->unsignedBigInteger('trims_store_mrr_detail_id')->nullable()
                ->after('trims_store_bin_card_detail_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_store_issue_details', function (Blueprint $table) {
            $table->dropColumn('trims_store_mrr_detail_id');
        });
    }
}
