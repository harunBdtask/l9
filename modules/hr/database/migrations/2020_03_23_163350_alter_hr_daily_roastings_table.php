<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterHrDailyRoastingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_daily_roastings', function (Blueprint $table) {
            $table->unsignedTinyInteger('off_day_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_daily_roastings', function (Blueprint $table) {
            $table->dropColumn('off_day_status');
        });
    }
}
