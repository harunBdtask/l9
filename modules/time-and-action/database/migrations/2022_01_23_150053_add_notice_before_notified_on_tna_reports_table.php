<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoticeBeforeNotifiedOnTnaReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_reports', function (Blueprint $table) {
            $table->boolean('notice_before_notified')->after('notice_before_date')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna_reports', function (Blueprint $table) {
            $table->dropColumn('notice_before_notified');
        });
    }
}
