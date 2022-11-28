<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadTimeWiseDaysInTnaTaskEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tna_task_entries', function (Blueprint $table) {
            $table->json('lead_time_wise_days')->nullable()->after('connected_task_id');
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
            $table->dropColumn('lead_time_wise_days');
        });
    }
}
