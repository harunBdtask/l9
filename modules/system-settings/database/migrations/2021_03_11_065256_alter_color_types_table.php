<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('color_types', function (Blueprint $table) {
            $table->integer('status')->nullable()->after('color_types')->comment('used in budget strip management');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('color_types', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
