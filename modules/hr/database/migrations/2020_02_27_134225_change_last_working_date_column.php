<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLastWorkingDateColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_terminations', function (Blueprint $table) {
            $table->renameColumn('last_working_date', 'last_working_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_terminations', function (Blueprint $table) {
            $table->renameColumn( 'last_working_day', 'last_working_date');
        });
    }
}
