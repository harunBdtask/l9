<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeVdqColumnInKnitCardYarnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knit_card_yarn_details', function (Blueprint $table) {
            $table->string('vdq')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knit_card_yarn_details', function (Blueprint $table) {
            $table->string('vdq')->nullable(false)->change();
        });
    }
}
