<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDyesChemicalTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemical_transactions', function (Blueprint $table) {
            $table->unsignedInteger('uom_id')->nullable();
            $table->string('sr_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('mrr_no')->nullable();
            $table->string('batch_no')->nullable();
            $table->integer('life_end_days')->nullable();

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
            $table->dropColumn('uom_id');
            $table->dropColumn('sr_no');
            $table->dropColumn('lot_no');
            $table->dropColumn('mrr_no');
            $table->dropColumn('batch_no');
            $table->dropColumn('life_end_days');
        });
    }
}
