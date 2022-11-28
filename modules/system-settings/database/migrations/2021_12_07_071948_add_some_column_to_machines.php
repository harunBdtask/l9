<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnToMachines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->unsignedInteger('knitting_floor_id')->nullable()->after('id');
            $table->string('machine_gg')->nullable()->after('machine_dia');
            $table->string('machine_type_info')->nullable()->after('machine_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn('knitting_floor_id');
            $table->dropColumn('machine_gg');
            $table->dropColumn('machine_type_info');
        });
    }
}
