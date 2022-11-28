<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSampleOrderRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_order_requisitions', function (Blueprint $table) {
            $table->unsignedInteger('team_leader_id')->nullable()->after('season_id');
            $table->tinyInteger('lab_test')->nullable();
            $table->string('booking_no')->nullable();
            $table->string('control_ref_no')->nullable();
            $table->string('ref_no')->nullable();
            $table->json('requis_details_cal')->nullable();
            $table->string('repeat_style_name')->nullable()->after('style_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_order_requisitions', function (Blueprint $table) {
            $table->dropColumn('team_leader_id');
            $table->dropColumn('lab_test');
            $table->dropColumn('booking_no');
            $table->dropColumn('control_ref_no');
            $table->dropColumn('ref_no');
            $table->dropColumn('requis_details_cal');
            $table->dropColumn('repeat_style_name');

        });
    }
}
