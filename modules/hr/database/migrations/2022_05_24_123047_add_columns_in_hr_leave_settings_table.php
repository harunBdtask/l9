<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInHrLeaveSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_leave_settings', function (Blueprint $table) {
            $table->string('employee_type')->after('id');
            $table->unsignedInteger('leave_types_id')->after('employee_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_leave_settings', function (Blueprint $table) {
            $table->dropColumn('employee_type');
            $table->dropColumn('leave_types_id');
        });
    }
}
