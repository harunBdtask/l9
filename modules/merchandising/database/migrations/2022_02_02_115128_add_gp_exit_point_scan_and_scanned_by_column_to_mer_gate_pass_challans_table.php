<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGpExitPointScanAndScannedByColumnToMerGatePassChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
            $table->integer('gp_exit_point_scanned_by')->nullable()->after('approved_by');
            $table->dateTime('gp_exit_point_scanned_at')->nullable()->after('deleted_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
            $table->dropColumn('gp_exit_point_scanned_by');
            $table->dropColumn('gp_exit_point_scanned_at');
        });
    }
}
