<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqIdToTrimsIssueReturnDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trims_issue_return_details', function (Blueprint $table) {
            $table->string('uniq_id')->change()->nullable();
            $table->string('item_color')->change()->nullable();
            $table->string('item_size')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trims_issue_return_details', function (Blueprint $table) {
            $table->string('uniq_id')->change()->nullable(false);
            $table->string('item_color')->change()->nullable(false);
            $table->string('item_size')->change()->nullable(false);
        });
    }
}
