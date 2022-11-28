<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAcUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ac_units', function (Blueprint $table) {
            $table->string('project_head_name', 60)->nullable();
            $table->string('phone_no', 60)->nullable();
            $table->string('email', 60)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ac_units', function (Blueprint $table) {
            $table->dropColumn('project_head_name');
            $table->dropColumn('phone_no');
            $table->dropColumn('email');
        });
    }
}
