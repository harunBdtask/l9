<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniqueIdColumnTypeInHrEmployeeOfficialInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employee_official_infos', function (Blueprint $table) {
            $table->string('unique_id')->change();
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
            $table->bigInteger('unique_id')->change();
        });
    }
}