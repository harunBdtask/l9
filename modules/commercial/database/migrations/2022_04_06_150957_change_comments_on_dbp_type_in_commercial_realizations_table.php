<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCommentsOnDbpTypeInCommercialRealizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commercial_realizations', function (Blueprint $table) {
            DB::statement("ALTER TABLE `commercial_realizations` CHANGE `dbp_type` `dbp_type` TINYINT(4) NULL DEFAULT NULL COMMENT '1=LDBC,2=FDBC,3=TT';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commercial_realizations', function (Blueprint $table) {
            DB::statement("ALTER TABLE `commercial_realizations` CHANGE `dbp_type` `dbp_type` TINYINT(4) NULL DEFAULT NULL COMMENT '1=LDBP,2=FDBP';");
        });
    }
}
