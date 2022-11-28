<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsToYarnReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_receives', function (Blueprint $table) {
            $table->tinyInteger('ready_to_approve')->default(0)->comment('0=No, 1=Yes')->after('remarks');
            $table->string('un_approve_request')->nullable()->after('ready_to_approve');
            $table->integer('step')->default(0)->after('un_approve_request');
            $table->string('approved_by')->after('step')->default('[]');
            $table->tinyInteger('is_approve')->nullable()->default(1)->comment('0=No, 1=Yes')->after('approved_by'); // is_approve 1 for existing data
            $table->date('approve_date')->nullable()->after('is_approve');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_receives', function (Blueprint $table) {
            $table->dropColumn('ready_to_approve');
            $table->dropColumn('un_approve_request');
            $table->dropColumn('step');
            $table->dropColumn('approved_by');
            $table->dropColumn('is_approve');
            $table->dropColumn('approve_date');
        });
    }
}
