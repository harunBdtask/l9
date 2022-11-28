<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManualAbsentStatusHrMachineAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_machine_attendances', function (Blueprint $table) {
            $table->tinyInteger('manual_absent_status')->default(0)->after('desc_c')->comment = "0=no,1=yes";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_machine_attendances', function (Blueprint $table) {
            $table->dropColumn('manual_absent_status');
        });
    }
}
