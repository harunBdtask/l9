<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnToDyesChemicalTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemical_transactions', function (Blueprint $table) {
            $table->string('ref')->nullable()->after('trn_type');
            $table->unsignedInteger('sub_store_id')->nullable()->after('ref');
            $table->unsignedInteger('trn_store')->nullable()->after('sub_store_id');
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
            $table->dropColumn('ref');
            $table->dropColumn('sub_store_id');
            $table->dropColumn('trn_store');
        });
    }
}
