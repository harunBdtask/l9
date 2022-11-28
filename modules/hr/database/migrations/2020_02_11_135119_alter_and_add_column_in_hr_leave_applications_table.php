<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAndAddColumnInHrLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_leave_applications', function (Blueprint $table) {
            $table->renameColumn('leave_date', 'leave_start');
            $table->date('leave_end')->after('leave_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_leave_applications', function (Blueprint $table) {
            $table->renameColumn('leave_start', 'leave_date');
            $table->dropColumn('leave_end');
        });
    }
}
