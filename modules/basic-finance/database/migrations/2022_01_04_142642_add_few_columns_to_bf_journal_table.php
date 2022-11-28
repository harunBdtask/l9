<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFewColumnsToBfJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_journal', function (Blueprint $table) {
            $table->unsignedInteger('department_id')->nullable()->after('trn_type');
            $table->unsignedInteger('cost_center_id')->nullable()->after('department_id');
            $table->unsignedInteger('currency_id')->nullable()->after('cost_center_id');
            $table->string('conversion_rate')->nullable()->after('currency_id');
            $table->string('fc')->nullable()->after('conversion_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_journal', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->dropColumn('cost_center_id');
            $table->dropColumn('currency_id');
            $table->dropColumn('conversion_rate');
            $table->dropColumn('fc');
        });
    }
}
