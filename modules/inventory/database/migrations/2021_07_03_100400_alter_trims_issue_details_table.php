<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTrimsIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_issue_details', function (Blueprint $table) {
            $table->dropColumn('trims_receive_id');
            $table->dropColumn('trims_receive_detail_id');
            $table->dropColumn('order_no');
            $table->json('po_no')->after('trims_issue_id')->nullable();
        });

        Schema::table('trims_issue_details', function (Blueprint $table) {
            $table->string('style_name', 40)->after('po_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_issue_details', function (Blueprint $table) {
            $table->unsignedInteger('trims_receive_id')->nullable();
            $table->unsignedInteger('trims_receive_detail_id')->nullable();
            $table->string('order_no', 30)->nullable();
            $table->dropColumn('po_no');
        });

        Schema::table('trims_issue_details', function (Blueprint $table) {
            $table->dropColumn('style_name');
        });
    }
}
