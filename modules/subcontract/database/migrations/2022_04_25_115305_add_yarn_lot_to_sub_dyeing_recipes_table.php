<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYarnLotToSubDyeingRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_dyeing_recipes', function (Blueprint $table) {
            $table->string('yarn_lot')->nullable()->after('ld_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_dyeing_recipes', function (Blueprint $table) {
            $table->dropColumn('yarn_lot');
        });
    }
}
