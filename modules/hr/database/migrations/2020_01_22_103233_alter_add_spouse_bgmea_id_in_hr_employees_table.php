<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddSpouseBgmeaIdInHrEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->string('spouse_name')->nullable()->after('nominee_relation_bn');
            $table->string('spouse_name_bn')->nullable()->after('spouse_name');
        });

        Schema::table('hr_employee_official_infos', function (Blueprint $table) {
            $table->string('bgmea_id')->nullable()->after('unique_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
