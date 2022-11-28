<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBinNumberToTrimsStoreIssues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_store_issues', function (Blueprint $table) {
            $table->string('bin_number')->nullable()->after('others');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_store_issues', function (Blueprint $table) {
            $table->dropColumn('bin_number');
        });
    }
}
