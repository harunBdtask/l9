<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBanglaFiledInDepartmentDesignationSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_departments', function (Blueprint $table) {
            $table->string('name_bn')->after('name')->collation = 'utf8_general_ci';
        });
        Schema::table('hr_designations', function (Blueprint $table) {
            $table->string('name_bn')->after('name')->collation = 'utf8_general_ci';
        });
        Schema::table('hr_sections', function (Blueprint $table) {
            $table->string('name_bn')->after('name')->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('department_designation_section', function (Blueprint $table) {
            //
        });
    }
}
