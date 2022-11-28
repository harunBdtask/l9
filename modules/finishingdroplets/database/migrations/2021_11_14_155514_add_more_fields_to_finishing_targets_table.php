<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToFinishingTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finishing_targets', function (Blueprint $table) {
            $table->dropColumn('iron_target');
            $table->dropColumn('qc_pass_target');
            $table->dropColumn('poly_target');
            $table->dropColumn('ctn_target');

            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->string('item_group')->nullable();
            $table->string('iron_work_hour')->nullable();
            $table->string('iron_man_power')->nullable();
            $table->string('iron_smv')->nullable();
            $table->string('iron_per_man_target')->nullable();
            $table->string('iron_hour_target')->nullable();
            $table->string('iron_day_total_target')->nullable();
            $table->string('poly_work_hour')->nullable();
            $table->string('poly_man_power')->nullable();
            $table->string('poly_smv')->nullable();
            $table->string('poly_per_man_target')->nullable();
            $table->string('poly_hour_target')->nullable();
            $table->string('poly_day_total_target')->nullable();
            $table->string('packing_work_hour')->nullable();
            $table->string('packing_man_power')->nullable();
            $table->string('packing_smv')->nullable();
            $table->string('packing_per_man_target')->nullable();
            $table->string('packing_hour_target')->nullable();
            $table->string('packing_day_total_target')->nullable();
            $table->string('total_efficiency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finishing_targets', function (Blueprint $table) {
            $table->unsignedInteger('iron_target')->nullable();
            $table->unsignedInteger('qc_pass_target')->nullable();
            $table->unsignedInteger('poly_target')->nullable();
            $table->unsignedInteger('ctn_target')->nullable();

            $table->dropColumn('buyer_id');
            $table->dropColumn('order_id');
            $table->dropColumn('item_id');
            $table->dropColumn('item_group');
            $table->dropColumn('iron_work_hour');
            $table->dropColumn('iron_man_power');
            $table->dropColumn('iron_smv');
            $table->dropColumn('iron_per_man_target');
            $table->dropColumn('iron_hour_target');
            $table->dropColumn('iron_day_total_target');
            $table->dropColumn('poly_work_hour');
            $table->dropColumn('poly_man_power');
            $table->dropColumn('poly_smv');
            $table->dropColumn('poly_per_man_target');
            $table->dropColumn('poly_hour_target');
            $table->dropColumn('poly_day_total_target');
            $table->dropColumn('packing_work_hour');
            $table->dropColumn('packing_man_power');
            $table->dropColumn('packing_smv');
            $table->dropColumn('packing_per_man_target');
            $table->dropColumn('packing_hour_target');
            $table->dropColumn('packing_day_total_target');
            $table->dropColumn('total_efficiency');
        });
    }
}
