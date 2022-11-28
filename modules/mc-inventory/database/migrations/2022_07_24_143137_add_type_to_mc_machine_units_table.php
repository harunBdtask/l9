<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToMcMachineUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mc_machine_units', function (Blueprint $table) {
            $table->string('type')->nullable()
                ->comment('1 == Rental,2 == In House')
                ->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mc_machine_units', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
