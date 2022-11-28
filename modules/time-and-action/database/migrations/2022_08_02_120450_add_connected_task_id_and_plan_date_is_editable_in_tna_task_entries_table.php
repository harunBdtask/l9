<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConnectedTaskIdAndPlanDateIsEditableInTnaTaskEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_task_entries', function (Blueprint $table) {
            $table->integer('connected_task_id')->nullable()->after('actual_date_range_calculate');
            $table->tinyInteger('plan_date_is_editable')->nullable()->after('connected_task_id')->comment('1 = yes, 2 = no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tna_task_entries', function (Blueprint $table) {
            $table->dropColumn(['connected_task_id', 'plan_date_is_editable']);
        });
    }
}
