<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsTypeInHrEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->bigInteger('zilla')->nullable()->change();
            $table->bigInteger('zilla_bn')->nullable()->change();
            $table->bigInteger('upazilla')->nullable()->change();
            $table->bigInteger('upazilla_bn')->nullable()->change();
            $table->bigInteger('present_address_zilla')->nullable()->change();
            $table->bigInteger('present_address_zilla_bn')->nullable()->change();
            $table->bigInteger('present_address_upazilla')->nullable()->change();
            $table->bigInteger('present_address_upazilla_bn')->nullable()->change();
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
            $table->string('zilla')->nullable()->change();
            $table->string('zilla_bn')->nullable()->change();
            $table->string('upazilla')->nullable()->change();
            $table->string('upazilla_bn')->nullable()->change();
            $table->string('present_address_zilla')->nullable()->change();
            $table->string('present_address_zilla_bn')->nullable()->change();
            $table->string('present_address_upazilla')->nullable()->change();
            $table->string('present_address_upazilla_bn')->nullable()->change();
        });
    }
}
