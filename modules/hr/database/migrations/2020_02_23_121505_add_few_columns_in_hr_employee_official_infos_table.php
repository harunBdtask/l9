<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFewColumnsInHrEmployeeOfficialInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employee_official_infos', function (Blueprint $table) {

            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('account_no')->nullable();
            $table->unsignedBigInteger('reporting_to')->nullable();
            $table->string('shift_enabled', 5)->default('no');
            $table->unsignedBigInteger('shift_id')->nullable()->comment('shift_id => when shift enabled');
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
            $table->dropColumn([
                'bank_id',
                'account_no',
                'reporting_to',
                'shift_enabled',
                'shift_id',
            ]);
        });
    }
}
