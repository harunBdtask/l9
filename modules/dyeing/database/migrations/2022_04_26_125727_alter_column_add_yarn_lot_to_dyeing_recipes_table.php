<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnAddYarnLotToDyeingRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyeing_recipes', function (Blueprint $table) {
            $table->string('yarn_lot')->nullable()->after('recipe_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dyeing_recipes', function (Blueprint $table) {
            $table->dropColumn('yarn_lot');
        });
    }
}
