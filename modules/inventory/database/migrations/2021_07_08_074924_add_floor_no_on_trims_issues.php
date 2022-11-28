<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFloorNoOnTrimsIssues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_issues', function (Blueprint $table) {
            $table->unsignedInteger('floor_no')->nullable()->after('factory_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_issues', function (Blueprint $table) {
            $table->dropColumn('floor_no');
        });
    }
}
