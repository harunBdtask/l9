<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerGatePassChallansAlterChallanNoColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
            $table->string('challan_no')->nullable()->change();
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
            $table->string('challan_no')->change();
        });
    }
}
