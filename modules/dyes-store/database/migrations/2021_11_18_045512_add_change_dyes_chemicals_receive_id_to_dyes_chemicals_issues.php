<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangeDyesChemicalsReceiveIdToDyesChemicalsIssues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemicals_issues', function (Blueprint $table) {
            $table->dropForeign('dyes_chemicals_issues_dyes_chemicals_receive_id_foreign');
            $table->dropColumn('dyes_chemicals_receive_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dyes_chemicals_issues', function (Blueprint $table) {
            $table->foreignId('dyes_chemicals_receive_id')->after('id')->constrained('dyes_chemicals_receive', 'id')->cascadeOnDelete();
        });
    }
}
