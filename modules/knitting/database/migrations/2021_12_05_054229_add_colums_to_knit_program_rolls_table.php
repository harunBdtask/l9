<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumsToKnitProgramRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knit_program_rolls', function (Blueprint $table) {
            $table->string('qc_length_in_mm')->after('qc_length_in_yards')->nullable();
            $table->tinyInteger('point_calculation_method')
                ->after('knitting_program_id')
                ->comment('1=InMeter,2=InYards')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knit_program_rolls', function (Blueprint $table) {
            $table->dropColumn('qc_length_in_mm');
            $table->dropColumn('point_calculation_method');
        });
    }
}
