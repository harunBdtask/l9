<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveYearColumnInHrFastivalLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_fastival_leaves', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->string('name', 300)->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_fastival_leaves', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->year('year')->after('id');
        });
    }
}
