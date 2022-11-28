<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreIdToDyesChemicalsIssues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyes_chemicals_issues', function (Blueprint $table) {
            $table->unsignedInteger('store_id')->nullable()->after('requisition');
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
            $table->dropColumn('store_id');
        });
    }
}
