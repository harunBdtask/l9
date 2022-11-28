<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoticeBeforeDateOnTnaReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_reports', function (Blueprint $table) {
            $table->date('notice_before_date')->after('notice_before')->nullable();
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
            $table->dropColumn('notice_before_date');
        });
    }
}
