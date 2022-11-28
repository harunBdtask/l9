<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToKnittingProgramCollarCuffProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knitting_program_collar_cuff_productions', function (Blueprint $table) {
            $table->unsignedBigInteger('knitting_program_collar_cuff_id')
                ->after('knitting_program_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knitting_program_collar_cuff_productions', function (Blueprint $table) {
            $table->dropColumn('knitting_program_collar_cuff_id');
        });
    }
}
