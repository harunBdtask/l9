<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameMachineLocationsToMcMachineLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mc_machine_locations', function (Blueprint $table) {
            Schema::rename('machine_locations', 'mc_machine_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mc_machine_locations', function (Blueprint $table) {
            Schema::rename('mc_machine_locations', 'machine_locations');
        });
    }
}
