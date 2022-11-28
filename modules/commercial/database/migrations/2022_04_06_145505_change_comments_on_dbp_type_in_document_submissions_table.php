<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCommentsOnDbpTypeInDocumentSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_submissions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `document_submissions` CHANGE `dbp_type` `dbp_type` TINYINT(4) NULL DEFAULT NULL COMMENT '1=LDBC,2=FDBC,3=TT';");
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
            DB::statement("ALTER TABLE `document_submissions` CHANGE `dbp_type` `dbp_type` TINYINT(4) NULL DEFAULT NULL COMMENT '1=LDBP,2=FDBP';");
        });
    }
}
