<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotifyToToBfDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_departments', function (Blueprint $table) {
            $table->tinyInteger('is_accounting')->default(0)->after('dept_details');
            $table->unsignedInteger('notify_to')->nullable()->after('is_accounting');
            $table->unsignedInteger('alternative_notify_to')->nullable()->after('notify_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_departments', function (Blueprint $table) {
            $table->dropColumn('is_accounting');
            $table->dropColumn('notify_to');
            $table->dropColumn('alternative_notify_to');
        });
    }
}
