<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToKnittingProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knitting_programs', function (Blueprint $table) {
            $table->string('machine_type_info')->nullable()->after('machine_gg');
            $table->string('knitting_charge')->nullable()->after('machine_type_info');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knitting_programs', function (Blueprint $table) {
            $table->dropColumn('machine_type_info');
            $table->dropColumn('knitting_charge');
        });
    }
}
