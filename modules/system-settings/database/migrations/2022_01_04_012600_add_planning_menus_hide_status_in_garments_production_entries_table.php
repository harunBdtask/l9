<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanningMenusHideStatusInGarmentsProductionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->tinyInteger('cutting_plan_menu_hide_status')->default(0)->comment("0=No,1=Yes")->after('erp_menu_view_status');
            $table->tinyInteger('sewing_plan_menu_hide_status')->default(0)->comment("0=No,1=Yes")->after('cutting_plan_menu_hide_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->dropColumn([
                'cutting_plan_menu_hide_status',
                'sewing_plan_menu_hide_status',
            ]);
        });
    }
}
