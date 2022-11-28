<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnYarnBrandInYarnIssueReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_issue_return_details', function (Blueprint $table) {
            $table->string('yarn_brand')->nullable()->after('yarn_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_issue_return_details', function (Blueprint $table) {
            $table->dropColumn('yarn_brand');
        });
    }
}
