<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToMerGatePassChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
            $table->tinyInteger('ready_to_approve')->after('goods_details')->nullable();
            $table->string('unapprove_request')->after('ready_to_approve')->nullable();
            $table->tinyInteger('is_approve')->after('unapprove_request')->nullable();
            $table->integer('step')->after('is_approve')->default(0);
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
            $table->dropColumn('ready_to_approve');
            $table->dropColumn('unapprove_request');
            $table->dropColumn('is_approve');
            $table->dropColumn('step');
        });
    }
}
