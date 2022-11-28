<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTrnTypeToDyesChemicalTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemical_transactions', function (Blueprint $table) {
            $table->string('trn_type', 250)->change();
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
            $table->string('trn_type', 10)->change();
        });
    }
}
