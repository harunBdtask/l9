<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToHrEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->text('village')->after('zilla_bn')->nullable();
            $table->text('village_bn')->after('village')->nullable();
            $table->text('present_address_zilla')->after('present_address_bn')->nullable();
            $table->text('present_address_zilla_bn')->after('present_address_zilla')->nullable();
            $table->text('present_address_village')->after('present_address_zilla_bn')->nullable();
            $table->text('present_address_village_bn')->after('present_address_village')->nullable();
            $table->text('present_address_upazilla')->after('present_address_village_bn')->nullable();
            $table->text('present_address_upazilla_bn')->after('present_address_upazilla')->nullable();
            $table->text('present_address_post_code')->after('present_address_upazilla_bn')->nullable();
            $table->text('present_address_post_code_bn')->after('present_address_post_code')->nullable();
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
            $table->dropColumn('village');
            $table->dropColumn('village_bn');
            $table->dropColumn('present_address_zilla');
            $table->dropColumn('present_address_zilla_bn');
            $table->dropColumn('present_address_village');
            $table->dropColumn('present_address_village_bn');
            $table->dropColumn('present_address_upazilla');
            $table->dropColumn('present_address_upazilla_bn');
            $table->dropColumn('present_address_post_code');
            $table->dropColumn('present_address_post_code_bn');
        });
    }
}
