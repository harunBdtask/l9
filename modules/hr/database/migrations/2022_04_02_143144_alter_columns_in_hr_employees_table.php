<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsInHrEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->renameColumn('zilla', 'zilla_id');
            $table->renameColumn('zilla_bn', 'zilla_bn_id');
            $table->renameColumn('upazilla', 'upazilla_id');
            $table->renameColumn('upazilla_bn', 'upazilla_bn_id');
            $table->renameColumn('present_address_zilla' ,'present_address_zilla_id');
            $table->renameColumn('present_address_zilla_bn', 'present_address_zilla_bn_id');
            $table->renameColumn('present_address_upazilla', 'present_address_upazilla_id');
            $table->renameColumn('present_address_upazilla_bn', 'present_address_upazilla_bn_id');
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
            $table->renameColumn('zilla_id', 'zilla');
            $table->renameColumn('zilla_bn_id', 'zilla_bn');
            $table->renameColumn('upazilla_id', 'upazilla');
            $table->renameColumn('upazilla_bn_id', 'upazilla_bn');
            $table->renameColumn('present_address_zilla_id' ,'present_address_zilla');
            $table->renameColumn('present_address_zilla_bn_id', 'present_address_zilla_bn');
            $table->renameColumn('present_address_upazilla_id', 'present_address_upazilla');
            $table->renameColumn('present_address_upazilla_bn_id', 'present_address_upazilla_bn');
        });
    }
}
