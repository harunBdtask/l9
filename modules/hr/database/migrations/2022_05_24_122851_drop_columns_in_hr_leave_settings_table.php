<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsInHrLeaveSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_leave_settings', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('name_bn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_leave_settings', function (Blueprint $table) {
            $table->string('name');
            $table->string('name_bn')->nullable();
        });
    }
}
