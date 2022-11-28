<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_departments', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->change();
        });

        Schema::table('hr_sections', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->change();
        });

        Schema::table('hr_designations', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->change();
        });

        Schema::table('hr_grades', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_departments', function (Blueprint $table) {
            $table->string('name_bn')->change();
        });

        Schema::table('hr_sections', function (Blueprint $table) {
            $table->string('name_bn')->change();
        });

        Schema::table('hr_designations', function (Blueprint $table) {
            $table->string('name_bn')->change();
        });

        Schema::table('hr_grades', function (Blueprint $table) {
            $table->string('name_bn')->change();
        });
    }
}
