<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLCReceiveDateToYarnReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_receives', function (Blueprint $table) {
            $table->date('lc_receive_date')->nullable()->after('lc_no');
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
            $table->dropColumn('lc_receive_date');
        });
    }
}
