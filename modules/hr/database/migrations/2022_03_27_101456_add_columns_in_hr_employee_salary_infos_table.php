<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInHrEmployeeSalaryInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employee_salary_infos', function (Blueprint $table) {
            $table->string('extra')->after('attendance_bonus')->nullable();
            $table->string('reason')->after('extra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_employee_salary_infos', function (Blueprint $table) {
            $table->dropColumn('extra');
            $table->dropColumn('reason');
        });
    }
}
