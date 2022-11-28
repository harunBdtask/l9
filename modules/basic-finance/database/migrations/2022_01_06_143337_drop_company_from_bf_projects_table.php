<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCompanyFromBfProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bf_projects', function (Blueprint $table) {
            $table->dropForeign('bf_projects_bf_company_id_foreign');
            $table->dropColumn('bf_company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bf_projects', function (Blueprint $table) {
            $table->foreignId('bf_company_id')->nullable()->after('id')->constrained('bf_companies');
        });
    }
}
