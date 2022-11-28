<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmvAndEfficiencyColumnsInSewingLineTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sewing_line_targets', function (Blueprint $table) {
            $table->string('smv', 30)->default(0)->after('helper');
            $table->string('efficiency', 30)->default(0)->after('smv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sewing_line_targets', function (Blueprint $table) {
            $table->dropColumn([
                'smv',
                'efficiency'
            ]);
        });
    }
}
