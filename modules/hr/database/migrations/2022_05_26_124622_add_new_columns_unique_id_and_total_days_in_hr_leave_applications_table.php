<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsUniqueIdAndTotalDaysInHrLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_leave_applications', function (Blueprint $table) {
            $table->string('unique_id')->after('employee_id');
            $table->integer('total_days')->nullable();
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
            $table->dropColumn('unique_id');
            $table->dropColumn('total_days');
        });
    }
}
