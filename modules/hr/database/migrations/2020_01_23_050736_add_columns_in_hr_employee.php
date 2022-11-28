<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInHrEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->string('emergency_contact_no_bn')
                ->change()
                ->nullable()
                ->after('nominee_relation_bn');
            $table->string('mobile_no')->change()->nullable()->after('emergency_contact_no_bn');
            $table->string('mobile_no_bn')->nullable()->after('mobile_no');
        });

        Schema::table('hr_employee_official_infos', function (Blueprint $table) {
            $table->string('date_of_joining_bn')->change()->nullable()->after('date_of_joining');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            if (Schema::hasColumn('hr_employees', 'emergency_contact_no_bn')) {
                $table->dropColumn('emergency_contact_no_bn');
            }
            $table->string('mobile_no')->change();
            $table->string('mobile_no_bn')->change();
        });
        Schema::table('hr_employee_official_infos', function (Blueprint $table) {
            $table->string('date_of_joining_bn')->change();
        });
    }
}
