<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiveIdToDyesChemicalTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemical_transactions', function (Blueprint $table) {
            $table->unsignedInteger('receive_id')->nullable()->after('trn_store');
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
            $table->dropColumn('receive_id');
        });
    }
}
