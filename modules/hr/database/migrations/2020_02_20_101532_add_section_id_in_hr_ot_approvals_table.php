<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSectionIdInHrOtApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_ot_approvals', function (Blueprint $table) {
            $table->unsignedInteger('ot_for')->nullable()->change()->comment = '1 = general, 2 = night';
            $table->unsignedInteger('department_id')->nullable()->after('file');
            $table->unsignedInteger('section_id')->nullable()->after('department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_ot_approvals', function (Blueprint $table) {
            $table->unsignedInteger('ot_for')->nullable()->change()->comment = '1 = general, 2 = shipment';
            $table->dropColumn([
                'department_id',
                'section_id',
            ]);
        });
    }
}
