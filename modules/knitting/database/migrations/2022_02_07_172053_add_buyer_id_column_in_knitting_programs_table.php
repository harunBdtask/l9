<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuyerIdColumnInKnittingProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knitting_programs', function (Blueprint $table) {
            $table->unsignedInteger('buyer_id')->after('factory_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knitting_programs', function (Blueprint $table) {
            $table->dropColumn('buyer_id');
        });
    }
}
