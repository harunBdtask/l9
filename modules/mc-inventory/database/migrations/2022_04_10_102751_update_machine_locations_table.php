<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMachineLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mc_machine_locations', function (Blueprint $table) {
            $table->string('address')->nullable()->change();
            $table->string('contact_no')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('attention')->nullable()->change();
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
            $table->string('address')->nullable(false)->change();
            $table->string('contact_no')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('attention')->nullable(false)->change();
        });
    }
}
