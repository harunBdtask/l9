<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectUnitVoucherNoRefNoToBfJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_journal', function (Blueprint $table) {
            $table->unsignedInteger('project_id')->nullable()->after('account_id');
            $table->unsignedInteger('unit_id')->nullable()->after('project_id');
            $table->string('voucher_no')->nullable()->after('unit_id');
            $table->string('reference_no')->nullable()->after('voucher_no');
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
            $table->dropColumn('project_id');
            $table->dropColumn('unit_id');
            $table->dropColumn('voucher_no');
            $table->dropColumn('reference_no');
        });
    }
}
