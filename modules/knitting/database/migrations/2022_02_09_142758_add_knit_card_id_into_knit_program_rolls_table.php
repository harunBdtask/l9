<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKnitCardIdIntoKnitProgramRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knit_program_rolls', function (Blueprint $table) {
            $table->unsignedBigInteger('knit_card_id')->after('knitting_program_id')->nullable();
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
            $table->dropColumn('knit_card_id');
        });
    }
}
