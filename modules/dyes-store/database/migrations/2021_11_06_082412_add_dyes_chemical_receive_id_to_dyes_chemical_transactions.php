<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDyesChemicalReceiveIdToDyesChemicalTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemical_transactions', function (Blueprint $table) {
            $table->unsignedInteger('dyes_chemical_receive_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dyes_chemical_transactions', function (Blueprint $table) {
            $table->dropColumn('dyes_chemical_receive_id');
        });
    }
}
