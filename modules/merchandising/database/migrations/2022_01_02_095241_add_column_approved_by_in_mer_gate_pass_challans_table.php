<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnApprovedByInMerGatePassChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
            $table->string('approved_by')->after('step')->default('[]');
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
            $table->dropColumn('approved_by');
        });
    }
}
