<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDyesChemicalIssueIdToDyesChemicalTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemical_transactions', function (Blueprint $table) {
            $table->dropForeign('dyes_chemical_transactions_dyes_chemical_receive_id_foreign');
            $table->dropColumn('dyes_chemical_receive_id');
            $table->dropForeign('dyes_chemical_transactions_receive_id_foreign');
            $table->dropColumn('receive_id');
            $table->unsignedInteger('dyes_chemical_issue_id')->nullable();
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
            $table->foreignId('dyes_chemical_receive_id')->constrained('dyes_chemicals_receive', 'id')->cascadeOnDelete();
            $table->foreignId('receive_id')->nullable()->constrained('dyes_chemical_transactions', 'id')->cascadeOnDelete();
            $table->dropColumn('dyes_chemical_issue_id');
        });
    }
}
