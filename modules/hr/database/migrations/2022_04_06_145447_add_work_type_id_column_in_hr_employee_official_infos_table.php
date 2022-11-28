<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkTypeIdColumnInHrEmployeeOfficialInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employee_official_infos', function (Blueprint $table) {
            $table->unsignedInteger('work_type_id')->after('designation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_employee_official_infos', function (Blueprint $table) {
            $table->dropColumn('work_type_id');
        });
    }
}
