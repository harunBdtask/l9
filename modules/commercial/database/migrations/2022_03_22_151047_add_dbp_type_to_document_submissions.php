<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDbpTypeToDocumentSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_submissions', function (Blueprint $table) {
            $table->tinyInteger('dbp_type')->nullable()->after('submission_type')->comment('1=LDBP,2=FDBP');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_submissions', function (Blueprint $table) {
            $table->dropColumn('dbp_type');
        });
    }
}
