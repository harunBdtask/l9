<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYarnBrandColumnInYarnIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_issue_details', function (Blueprint $table) {
            $table->string('yarn_brand')->after('yarn_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_issue_details', function (Blueprint $table) {
            $table->dropColumn('yarn_brand');
        });
    }
}
