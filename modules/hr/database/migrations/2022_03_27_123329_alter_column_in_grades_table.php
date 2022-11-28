<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnInGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_grades', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable()->after('name_bn');
            $table->string('basic_salary')->nullable()->after('group_id');
            $table->string('home_rent')->nullable()->after('basic_salary');
            $table->string('total_salary')->nullable()->comment('Fees included from group')->after('home_rent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_grades', function (Blueprint $table) {
            $table->dropColumn([
                'group_id',
                'basic_salary',
                'home_rent',
                'total_salary'
            ]);
        });
    }
}
