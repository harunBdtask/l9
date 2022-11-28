<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSystemGenerateIdToDyesChemicalIssueReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemical_issue_return', function (Blueprint $table) {
            $table->string('system_generate_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dyes_chemical_issue_return', function (Blueprint $table) {
            $table->dropColumn('system_generate_id');
        });
    }
}
