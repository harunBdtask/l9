<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTpCostingFieldToPreCostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_costings', function (Blueprint $table) {
            $table->string('tp_file_2')->nullable()->after('tp_file');
            $table->string('tp_file_3')->nullable()->after('tp_file_2');
            $table->string('costing_file_2')->nullable()->after('costing_file');
            $table->string('costing_file_3')->nullable()->after('costing_file_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_costings', function (Blueprint $table) {
            $table->dropColumn('tp_file_2');
            $table->dropColumn('tp_file_3');
            $table->dropColumn('costing_file_2');
            $table->dropColumn('costing_file_3');
        });
    }
}
