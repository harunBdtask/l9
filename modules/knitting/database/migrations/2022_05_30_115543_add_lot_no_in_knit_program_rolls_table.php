<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLotNoInKnitProgramRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knit_program_rolls', function (Blueprint $table) {
            $table->string('lot_no')->after('roll_weight')->nullable();
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
            $table->dropColumn('lot_no');
        });
    }
}
